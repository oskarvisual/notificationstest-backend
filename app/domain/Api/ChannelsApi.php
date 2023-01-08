<?php

namespace App\Domain\Api;

use App\Domain\Entity\Channel;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\JsonResponse;

class ChannelsApi
{
    private array $channels = array();

    public function __construct(array $channels)
    {
        $this->channels = $channels;
    }

    public function index(Request $request): JsonResponse
    {
        return new JsonResponse(200, $this->channels);
    }
}
