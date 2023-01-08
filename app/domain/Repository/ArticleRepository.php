<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Article;

interface ArticleRepository
{
    public function getAll(): array;
    public function getById(int $id): ?Article;
    public function add(Article $article): ?int;
    public function update(Article $article): bool;
    public function delete(int $id): bool;
}
