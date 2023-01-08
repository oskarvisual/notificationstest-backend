<?php

namespace App\Domain\Entity;

class Notification
{
    private int $id;
    private string $channel;
    private string $subject;
    private string $body;
    private string $sentAt;
    private int $userId;

    public function __construct(
        int $id,
        string $channel,
        string $subject,
        string $body,
        string $sentAt,
        int $userId
    ) {
        $this->id = $id;
        $this->channel = $channel;
        $this->subject = $subject;
        $this->body = $body;
        $this->sentAt = $sentAt;
        $this->userId = $userId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getsentAt(): string
    {
        return $this->sentAt;
    }

    public function setsentAt(string $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }
}
