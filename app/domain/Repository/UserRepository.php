<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepository
{
    public function getAll(): array;
    public function getById(int $id): ?User;
    public function getBySubscribed(int $categoryId): array;
    public function add(User $user): int;
    public function update(User $user): bool;
    public function delete(int $id): bool;
}
