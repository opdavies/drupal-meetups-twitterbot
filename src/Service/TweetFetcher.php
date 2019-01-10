<?php

namespace App\Service;

use Codebird\Codebird;
use Tightenco\Collect\Support\Collection;

class TweetFetcher
{
    /** @var \Codebird\Codebird */
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
        $codebird::setConsumerKey(
            getenv('TWITTER_CONSUMER_KEY'),
            getenv('TWITTER_CONSUMER_SECRET')
        );

        $codebird = $codebird::getInstance();

        $codebird->setToken(
            getenv('TWITTER_ACCESS_TOKEN'),
            getenv('TWITTER_ACCESS_SECRET')
        );

        $this->codebird = $codebird;
    }

    public function getTweets(): Collection
    {
        $response = collect($this->codebird->search_tweets([
            'q' => collect($this->params()->all())->implode(' AND '),
            // 'since_id' => $this->lastTweetId,
        ]));

        if ($response->get('httpstatus') != 200) {
            dump($response);
        }

        return collect($response->get('statuses'))
            ->map(function (\stdClass $tweet) {
                return [
                    'id' => $tweet->id,
                    'created' => strtotime($tweet->created_at),
                    'text' => $tweet->text,
                    'author' => $tweet->user->screen_name,
                ];
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
