<?php

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Entity\Channel;

use App\Domain\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(): array
    {
        return $this->userRepository->getAll();
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->getById($id);
    }

    public function getBySubscribed(int $id): array
    {
        return $this->userRepository->getBySubscribed($id);
    }

    public function add(
        string $name,
        string $email,
        string $phoneNumber,
        array $subscribed,
        array $channels
    ): ?User {
        $user = new User(0, $name, $email, $phoneNumber, $subscribed, $channels);
        $userId = $this->userRepository->add($user);
        $user->setId($userId);
        return $user;
    }

    public function update(
        int $id,
        string $name,
        string $email,
        string $phoneNumber,
        array $subscribed,
        array $channels
    ): ?User {
        $user = new User($id, $name, $email, $phoneNumber, $subscribed, $channels);
        return ($this->userRepository->update($user)) ? $user : null;
    }

    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
