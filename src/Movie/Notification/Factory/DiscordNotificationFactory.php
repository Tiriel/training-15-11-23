<?php

namespace App\Movie\Notification\Factory;

use App\Movie\Notification\Notifications\DiscordNotification;
use Symfony\Component\Notifier\Notification\Notification;

class DiscordNotificationFactory implements NotificationFactoryInterface
{

    public function createNotification(string $subject): Notification
    {
        return new DiscordNotification($subject);
    }

    public static function getIndex(): string
    {
        return 'discord';
    }
}
