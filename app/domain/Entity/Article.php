<?php

namespace App\Domain\Entity;

class Article
{
    private int $id;
    private string $title;
    private string $content;
    private int $categoryId;

    public function __construct(int $id, string $title, string $content, int $categoryId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->categoryId = $categoryId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }
}
