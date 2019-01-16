<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TweetRepository")
 */
class Tweet
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
//    private $tweet_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $retweeted;

    public function getId(): ?int
    {
        return $this->id;
    }

//    public function getTweetId(): ?int
//    {
//        return $this->tweet_id;
//    }

//    public function setTweetId(int $tweet_id): self
//    {
//        $this->tweet_id = $tweet_id;
//
//        return $this;
//    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated(string $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getRetweeted(): ?string
    {
        return $this->retweeted;
    }

    public function setRetweeted(?string $retweeted): self
    {
        $this->retweeted = $retweeted;

        return $this;
    }
}
