<?php

namespace App\Service;

use App\Entity\Tweet;
use App\Repository\TweetRepository;
use Tightenco\Collect\Support\Collection;

class TweetFetcher
{
    /** @var \App\Service\Codebird */
    private $codebird;

    private $accounts = [
        'drupalbristol',
        'drupalsomerset',
        'DrupalSurrey',
        'DrupalWLondon',
        'DrupalYorkshire',
        'nwdug',
        'opdavies',
        'opdavies_',
        'OxDUG',
        'swdug',
    ];

    private $hashtags = [
        'drupalmeetups',
        'drupalmeetup',
    ];

    /**
     * @var \App\Repository\TweetRepository
     */
    private $tweetRepository;

    public function __construct(Codebird $codebird, TweetRepository $tweetRepository)
    {
        $this->codebird = $codebird;
        $this->tweetRepository = $tweetRepository;
    }

    public function getTweets(): Collection
    {
        $latestTweet = $this->tweetRepository->findNewestTweet();

        $response = collect($this->codebird->get()->search_tweets([
            'q' => collect($this->params()->all())->implode(' AND '),
             'since_id' => $latestTweet ? $latestTweet->getId() : null,
        ]));

        if ($response->get('httpstatus') != 200) {
            dump($response);
        }

        return collect($response->get('statuses'))
            ->map(function (\stdClass $status) {
                return tap(new Tweet(), function (Tweet $tweet) use ($status) {
                    $tweet->setId($status->id);
                    $tweet->setText($status->text);
                    $tweet->setCreated(strtotime($status->created_at));
                    $tweet->setAuthor($status->user->screen_name);

                    $this->entityManager->persist($tweet);
                });
            })->reverse();
    }

    private function params(): Collection
    {
        return tap(collect(), function (Collection $params) {
            // Add account names.
            $params->push(
              collect($this->accounts)->map(function (string $account) {
                  return "from:{$account}";
              })->implode(' OR ')
            );

            // Add hashtags.
            $params->push(
                collect($this->hashtags)->map(function (string $hashtag) {
                    return "#{$hashtag}";
                })->implode(' OR ')
            );
        });
    }
}
