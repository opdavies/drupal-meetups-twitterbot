<?php

namespace App\Command;

use App\Entity\Tweet;
use App\Service\TweetFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchTweetsCommand extends Command
{
    protected static $defaultName = 'app:fetch-tweets';

    /**
     * @var \App\Service\TweetFetcher
     */
    private $tweetFetcher;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TweetFetcher $tweetFetcher, EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->tweetFetcher = $tweetFetcher;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->tweetFetcher->getTweets()->each(function (Tweet $tweet) {
            $this->entityManager->persist($tweet);
        });

        $this->entityManager->flush();
    }
}
