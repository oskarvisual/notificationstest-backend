<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;

class MySqlUserRepository implements UserRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $users = array();
        
        foreach ($rows as $row) {
            $user = new User(
                intval($row["id"]),
                $row["name"],
                $row["email"],
                $row["phone_number"],
                json_decode($row["subscribed"], true),
                json_decode($row["channels"], true)
            );
            $users[] = $user;
        }
        
        return $users;
    }

    public function getById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute(array($id));
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $user = new User(
            intval($row["id"]),
            $row["name"],
            $row["email"],
            $row["phone_number"],
            json_decode($row["subscribed"], true),
            json_decode($row["channels"], true)
        );

        return $user;
    }

    public function getBySubscribed(int $categoryId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE JSON_CONTAINS(subscribed, ?)");
        $stmt->execute(array(sprintf('[%s]', $categoryId)));
        $rows = $stmt->fetchAll();
        $users = array();
        
        foreach ($rows as $row) {
            $user = new User(
                intval($row["id"]),
                $row["name"],
                $row["email"],
                $row["phone_number"],
                json_decode($row["subscribed"], true),
                json_decode($row["channels"], true)
            );
            $users[] = $user;
        }
        
        return $users;
    }

    public function add(User $user): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, phone_number, subscribed, channels) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute(array(
            $user->getName(),
            $user->getEmail(),
            $user->getPhoneNumber(),
            json_encode($user->getSubscribed()),
            json_encode($user->getChannels())
        ));
        return intval($this->pdo->lastInsertId());
    }

    public function update(User $user): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET name = ?, email = ?, phone_number = ?, subscribed = ?, channels = ? WHERE id = ?"
        );
        $stmt->execute(array(
            $user->getName(),
            $user->getEmail(),
            $user->getPhoneNumber(),
            json_encode($user->getSubscribed()),
            json_encode($user->getChannels()),
            $user->getId()
        ));
        return ($stmt->rowCount() == 1);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute(array($id));
        return ($stmt->rowCount() == 1);
    }
}
