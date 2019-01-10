<?php

namespace App\Command;

use App\Service\TweetFetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RetweetTweetsCommand extends Command
{
    protected static $defaultName = 'app:retweet-tweets';

    private $tweetFetcher;

    public function __construct(TweetFetcher $tweetFetcher)
    {
        parent::__construct();

        $this->tweetFetcher = $tweetFetcher;
    }
    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->tweetFetcher->getTweets()->each(function (array $tweet) {
            $this->retweeter->retweet();
        });
    }
}