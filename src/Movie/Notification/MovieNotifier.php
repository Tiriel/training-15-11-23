<?php

namespace App\Movie\Notification;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Notification\Factory\NotificationFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    /** @var NotificationFactoryInterface[] */
    private iterable $factories;

    public function __construct(
        private readonly NotifierInterface $notifier,
        #[TaggedIterator(tag: 'app.notification_factory', defaultIndexMethod: 'getIndex')]
        iterable $factories,
    ) {
        $this->factories = $factories;
    }

    public function notifyNewMovie(User $user, Movie $movie): void
    {
        $msg = sprintf("The movie %s has been added to the database!", $movie->getTitle());

        $notification = $this->factories[$user->getPreferredChannel()]
            ->createNotification($msg);

        $this->notifier->send($notification, new Recipient($user->getEmail()));
    }
}
