<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\Article;
use App\Domain\Repository\ArticleRepository;

class MySqlArticleRepository implements ArticleRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles ORDER BY articles.id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $articles = array();
        
        foreach ($rows as $row) {
            $article = new Article(
                intval($row["id"]),
                $row["title"],
                $row["content"],
                intval($row["category_id"])
            );
            $articles[] = $article;
        }
        
        return $articles;
    }

    public function getById(int $id): ?Article
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute(array($id));
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $article = new Article(
            intval($row["id"]),
            $row["title"],
            $row["content"],
            intval($row["category_id"])
        );

        return $article;
    }

    public function add(Article $article): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO articles (title, content, category_id) VALUES (?, ?, ?)");
        $stmt->execute(array(
            $article->getTitle(),
            $article->getContent(),
            $article->getCategoryId()
        ));
        return intval($this->pdo->lastInsertId());
    }

    public function update(Article $article): bool
    {
        $stmt = $this->pdo->prepare("UPDATE articles SET title = ?, content = ?, category_id = ? WHERE id = ?");
        $stmt->execute(array(
            $article->getTitle(),
            $article->getContent(),
            $article->getCategoryId(),
            $article->getId()
        ));
        return ($stmt->rowCount() == 1);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute(array($id));
        return ($stmt->rowCount() == 1);
    }
}
