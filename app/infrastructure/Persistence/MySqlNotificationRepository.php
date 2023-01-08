<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\Notification;
use App\Domain\Repository\NotificationRepository;

class MySqlNotificationRepository implements NotificationRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications ORDER BY id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $notifications = array();
        
        foreach ($rows as $row) {
            $notification = new Notification(
                intval($row["id"]),
                $row["channel"],
                $row["subject"],
                $row["body"],
                $row["sent_at"],
                intval($row["user_id"])
            );
            $notifications[] = $notification;
        }
        
        return $notifications;
    }

    public function getById(int $id): ?Notification
    {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE id = ?");
        $stmt->execute(array($id));
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $notification = new Notification(
            intval($row["id"]),
            $row["channel"],
            $row["subject"],
            $row["body"],
            $row["sent_at"],
            intval($row["user_id"])
        );

        return $notification;
    }

    public function add(Notification $notification): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO notifications 
        (channel, subject, body, sent_at, user_id) 
        VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(array(
            $notification->getChannel(),
            $notification->getSubject(),
            $notification->getBody(),
            $notification->getsentAt(),
            $notification->getUserId()
        ));
        return intval($this->pdo->lastInsertId());
    }

    public function update(Notification $notification): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE notifications 
            SET channel = ?, subject = ?, body = ?, sent_at = ?, user_id = ? 
            WHERE id = ?"
        );
        $stmt->execute(array(
            $notification->getChannel(),
            $notification->getSubject(),
            $notification->getBody(),
            $notification->getsentAt(),
            $notification->getUserId(),
            $notification->getId()
        ));
        return ($stmt->rowCount() == 1);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM notifications WHERE id = ?");
        $stmt->execute(array($id));
        return ($stmt->rowCount() == 1);
    }
}
