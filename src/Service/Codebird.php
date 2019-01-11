<?php

namespace App\Service;

class Codebird
{
    /**
     * @var \Codebird\Codebird
     */
    private $codebird;

    public function __construct(\Codebird\Codebird $codebird)
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

    public function get(): \Codebird\Codebird
    {
        return $this->codebird;
    }
}
