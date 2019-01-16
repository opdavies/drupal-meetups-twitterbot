<?php

namespace App\Command;

use App\Entity\Tweet;
use App\Repository\TweetRepository;
use App\Service\Retweeter;
use App\Service\TweetFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RetweetTweetsCommand extends Command
{
    protected static $defaultName = 'app:retweet-tweets';

    private $tweetFetcher;

    private $retweeter;

    /**
     * @var \App\Repository\TweetRepository
     */
    private $tweetRepository;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        TweetFetcher $tweetFetcher,
        TweetRepository $tweetRepository,
        Retweeter $retweeter,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();

        $this->tweetFetcher = $tweetFetcher;
        $this->retweeter = $retweeter;
        $this->tweetRepository = $tweetRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Retweet one or more stored tweets')
            ->addOption('number', null, InputOption::VALUE_OPTIONAL, 'Specify how many tweets to retweet.', 1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->tweetRepository->getUntweetedTweets($input->getOption('number'))->each(function (Tweet $tweet) {
            $this->retweeter->retweet($tweet);

            $tweet->setRetweeted(time());

            $this->entityManager->persist($tweet);
        });

        $this->entityManager->flush();
    }
}
