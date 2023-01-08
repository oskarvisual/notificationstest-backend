<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Article;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $phoneNumber;
    private array $subscribed;
    private array $channels;

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $phoneNumber,
        array $subscribed,
        array $channels
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->subscribed = $subscribed;
        $this->channels = $channels;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getSubscribed(): array
    {
        return $this->subscribed;
    }

    public function setSubscribed(array $subscribed): void
    {
        $this->subscribed = $subscribed;
    }

    public function getChannels(): array
    {
        return $this->channels;
    }

    public function setChannels(array $channels): void
    {
        $this->channels = $channels;
    }
}
