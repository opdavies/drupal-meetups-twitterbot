<?php

namespace App\Service;

use App\Model\Tweet;

class Retweeter
{
    /** @var \App\Service\Codebird */
    private $codebird;

    public function __construct(Codebird $codebird)
    {
        $this->codebird = $codebird;
    }

    public function retweet(Tweet $tweet): void
    {
        $this->codebird->get()->statuses_retweet_ID([
          'id' => $tweet->getId(),
        ]);
    }
}
