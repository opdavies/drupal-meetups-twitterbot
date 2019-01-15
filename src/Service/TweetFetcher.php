<?php

namespace App\Service;

use App\Entity\Tweet;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \App\Service\TweetRepository
     */
    private $tweetRepository;

    public function __construct(Codebird $codebird, EntityManagerInterface $entityManager, TweetRepository $tweetRepository)
    {
        $this->codebird = $codebird;
        $this->entityManager = $entityManager;
        $this->tweetRepository = $tweetRepository;
    }

    public function getTweets(): Collection
    {
        $params = ['q' => collect($this->params()->all())->implode(' AND ')];
        if ($newestTweet = $this->tweetRepository->findNewestTweet()) {
            $params['since_id'] = $newestTweet->getId();
        }

        $response = collect($this->codebird->get()->search_tweets($params));

        if ($response->get('httpstatus') != 200) {
            dump($response);
        }

        $tweets = collect($response->get('statuses'))
            ->map(function (\stdClass $status) {
                return tap(new Tweet(), function (Tweet $tweet) use ($status) {
                    $tweet->setId($status->id);
                    $tweet->setText($status->text);
                    $tweet->setCreated(strtotime($status->created_at));
                    $tweet->setAuthor($status->user->screen_name);

                    $this->entityManager->persist($tweet);
                });
            })->reverse();

        $this->entityManager->flush();

        return $tweets;
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
