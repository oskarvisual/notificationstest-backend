<?php

namespace App\Domain\Observer;

use App\Domain\Repository\NotificationRepository;

interface Subject
{
    public function addObserver(array $observer): void;
    public function removeObserver(array $observer): void;
    public function notifyObservers(NotificationRepository $notificationRepository): void;
}
