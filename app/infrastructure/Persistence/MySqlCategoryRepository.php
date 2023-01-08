<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\Category;
use App\Domain\Repository\CategoryRepository;

class MySqlCategoryRepository implements CategoryRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $categories = array();
        
        foreach ($rows as $row) {
            $category = new Category(
                intval($row["id"]),
                $row["title"],
                $row["content"]
            );
            $categories[] = $category;
        }
        
        return $categories;
    }

    public function getById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute(array($id));
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $category = new Category(
            intval($row["id"]),
            $row["title"],
            $row["content"]
        );

        return $category;
    }

    public function add(Category $category): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (title, content) VALUES (?, ?)");
        $stmt->execute(array(
            $category->getTitle(),
            $category->getContent()
        ));
        return intval($this->pdo->lastInsertId());
    }

    public function update(Category $category): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET title = ?, content = ? WHERE id = ?");
        $stmt->execute(array(
            $category->getTitle(),
            $category->getContent(),
            $category->getId()
        ));
        return ($stmt->rowCount() == 1);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute(array($id));
        return ($stmt->rowCount() == 1);
    }
}
