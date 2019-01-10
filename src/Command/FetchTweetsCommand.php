<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchTweetsCommand extends Command
{
    protected static $defaultName = 'app:fetch-tweets';

    /**
     * @var \App\Service\TweetFetcher
     */
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
        $io = new SymfonyStyle($input, $output);

        dump($this->tweetFetcher->getTweets());
    }
}
