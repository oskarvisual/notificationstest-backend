<?php

namespace App\Domain\Service;

use App\Domain\Entity\Notification;
use App\Domain\Repository\NotificationRepository;

class NotificationService
{
    private NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getAll(): array
    {
        return $this->notificationRepository->getAll();
    }

    public function getById(int $id): ?Notification
    {
        return $this->notificationRepository->getById($id);
    }

    public function add(
        string $channel,
        string $subject,
        string $body,
        string $sentAt,
        int $userId
    ): ?Notification {
        $notification = new Notification(0, $channel, $subject, $body, $sentAt, $userId);
        $notificationId = $this->notificationRepository->add($notification);
        $notification->setId($notificationId);
        return $notification;
    }

    public function update(
        int $id,
        string $channel,
        string $subject,
        string $body,
        string $sentAt,
        int $userId
    ): ?Notification {
        $notification = new Notification($id, $channel, $subject, $body, $sentAt, $userId);
        return ($this->notificationRepository->update($notification)) ? $notification : null;
    }

    public function delete(int $id): bool
    {
        return $this->notificationRepository->delete($id);
    }
}
