<?php

namespace App\Service;

use App\Model\Tweet;
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
        'OxDUG',
        'swdug',
    ];

    private $hashtags = [
        'drupalmeetups',
        'drupalmeetup',
    ];

    public function __construct(Codebird $codebird)
    {
        $this->codebird = $codebird;
    }

    public function getTweets(): Collection
    {
        $response = collect($this->codebird->get()->search_tweets([
            'q' => collect($this->params()->all())->implode(' AND '),
            // 'since_id' => $this->lastTweetId,
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
