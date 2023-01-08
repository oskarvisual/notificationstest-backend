<?php

namespace App\Domain\Api;

use App\Domain\Entity\Notification;
use App\Domain\Service\NotificationService;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\JsonResponse;

class NotificationsApi
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request): JsonResponse
    {
        $notifications = array();
        foreach ($this->notificationService->getAll() as $notification) {
            $notifications[] = array(
                "id" => $notification->getId(),
                "channel" => $notification->getChannel(),
                "subject" => $notification->getSubject(),
                "body" => $notification->getBody(),
                "sentAt" => $notification->getsentAt(),
                "userId" => $notification->getUserId()
            );
        }
        return new JsonResponse(200, $notifications);
    }

    public function show(Request $request): JsonResponse
    {
        $notificationId = intval(@$request->getQuery("id"));
        $notification = $this->notificationService->getById($notificationId);
        if (!$notification) {
            return new JsonResponse(404, array("error" => "Notification not found"));
        }
        return new JsonResponse(200, array(
            "id" => $notification->getId(),
            "channel" => $notification->getChannel(),
            "subject" => $notification->getSubject(),
            "body" => $notification->getBody(),
            "sentAt" => $notification->getsentAt(),
            "userId" => $notification->getUserId()
        ));
    }

    public function delete(Request $request): JsonResponse
    {
        $notificationId = intval(@$request->getQuery("id"));
        $notification = $this->notificationService->delete($notificationId);
        if (!$notification) {
            return new JsonResponse(204, array("error" => "Notification not found or not deleted"));
        }
        return new JsonResponse(200, array("success" => "Notification deleted"));
    }
}
