<?php

namespace App\Repository;

use App\Entity\Tweet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Tightenco\Collect\Support\Collection;

/**
 * @method Tweet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tweet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tweet[]    findAll()
 * @method Tweet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TweetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tweet::class);
    }

    public function findNewestTweet(): ?Tweet
    {
        $result = $this->createQueryBuilder('t')
            ->orderBy('t.created', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return collect($result)->first();
    }


    public function getUntweetedTweets(int $limit): Collection
    {
        return collect(
            $this->createQueryBuilder('t')
                ->where('t.retweeted is NULL')
                ->orderBy('t.created', 'asc')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult()
        );
    }

    /*
    public function findOneBySomeField($value): ?Tweet
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
