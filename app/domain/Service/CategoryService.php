<?php

namespace App\Domain\Service;

use App\Domain\Entity\Category;
use App\Domain\Repository\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll(): array
    {
        return $this->categoryRepository->getAll();
    }

    public function getById(int $id): ?Category
    {
        return $this->categoryRepository->getById($id);
    }

    public function add(string $title, string $content): ?Category
    {
        $category = new Category(0, $title, $content);
        $categoryId = $this->categoryRepository->add($category);
        $category->setId($categoryId);
        return $category;
    }

    public function update(int $id, string $title, string $content): ?Category
    {
        $category = new Category($id, $title, $content);
        return ($this->categoryRepository->update($category)) ? $category : null;
    }

    public function delete(int $id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}
