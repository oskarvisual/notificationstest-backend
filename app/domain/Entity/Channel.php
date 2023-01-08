<?php

namespace App\Domain\Entity;

class Channel
{
    private array $channels;

    public function __construct()
    {
        $this->channels = array();
    }

    public function addChannel(string $key, string $channel): void
    {
        $this->channels[$key] = $channel;
    }

    public function getChannels(): array
    {
        return $this->channels;
    }
}
