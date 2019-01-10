<?php

namespace App\Model;

class Tweet
{
    private $text;
    private $created;
    private $author;
    private $id;

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function setCreated(string $created): void
    {
        $this->created = $created;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }
}
