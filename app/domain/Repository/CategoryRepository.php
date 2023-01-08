<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Category;

interface CategoryRepository
{
    public function getAll(): array;
    public function getById(int $id): ?Category;
    public function add(Category $category): int;
    public function update(Category $category): bool;
    public function delete(int $id): bool;
}
