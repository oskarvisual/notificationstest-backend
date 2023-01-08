<?php

namespace App\Domain\Api;

use App\Domain\Entity\User;
use App\Domain\Service\UserService;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\JsonResponse;

class UsersApi
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $users = array();
        foreach ($this->userService->getAll() as $user) {
            $users[] = array(
                "id" => $user->getId(),
                "name" => $user->getName(),
                "email" => $user->getEmail(),
                "phoneNumber" => $user->getPhoneNumber(),
                "subscribed" => $user->getSubscribed(),
                "channels" => $user->getChannels()
            );
        }
        return new JsonResponse(200, $users);
    }

    public function show(Request $request): JsonResponse
    {
        $userId = intval(@$request->getQuery('id'));
        $user = $this->userService->getById($userId);
        if (!$user) {
            return new JsonResponse(404, array("error" => "User not found"));
        }
        return new JsonResponse(200, array(
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "phoneNumber" => $user->getPhoneNumber(),
            "subscribed" => $user->getSubscribed(),
            "channels" => $user->getChannels()
        ));
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->getRequests();
        
        $user = $this->userService->add(
            @$data['name'],
            @$data['email'],
            @$data['phoneNumber'],
            array_map(
                'intval',
                (is_array(@$data['subscribed'])) ? @$data['subscribed'] : explode(',', @$data['subscribed'])
            ),
            (is_array(@$data['channels'])) ? @$data['channels'] : explode(',', @$data['channels'])
        );
        return new JsonResponse(201, array(
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "phoneNumber" => $user->getPhoneNumber(),
            "subscribed" => $user->getSubscribed(),
            "channels" => $user->getChannels()
        ));
    }

    public function update(Request $request): JsonResponse
    {
        $userId = intval(@$request->getQuery('id'));
        $data = $request->getRequests();

        $user = $this->userService->update(
            @$userId,
            @$data['name'],
            @$data['email'],
            @$data['phoneNumber'],
            array_map(
                'intval',
                (is_array(@$data['subscribed'])) ? @$data['subscribed'] : explode(',', @$data['subscribed'])
            ),
            (is_array(@$data['channels'])) ? @$data['channels'] : explode(',', @$data['channels'])
        );
        if (!$user) {
            return new JsonResponse(204, array("error" => "User not found or not updated"));
        }
        return new JsonResponse(200, array(
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "phoneNumber" => $user->getPhoneNumber(),
            "subscribed" => $user->getSubscribed(),
            "channels" => $user->getChannels()
        ));
    }

    public function delete(Request $request): JsonResponse
    {
        $userId = intval(@$request->getQuery('id'));
        $user = $this->userService->delete($userId);
        if (!$user) {
            return new JsonResponse(204, array("error" => "User not found or not deleted"));
        }
        return new JsonResponse(200, array("success" => "User deleted"));
    }
}
