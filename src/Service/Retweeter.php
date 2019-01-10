<?php

namespace App\Service;

use App\Model\Tweet;
use Codebird\Codebird;

class Retweeter
{

    private $codebird;

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

    public function retweet(Tweet $tweet): void
    {
        $this->codebird->statuses_retweet_ID([
          'id' => $tweet->getId(),
        ]);
    }
}
