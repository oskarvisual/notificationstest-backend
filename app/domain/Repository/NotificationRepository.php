<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Notification;

interface NotificationRepository
{
    public function getAll(): array;
    public function getById(int $id): ?Notification;
    public function add(Notification $notification): int;
    public function update(Notification $notification): bool;
    public function delete(int $id): bool;
}
