<?php

namespace App\EventListener;

use App\Movie\Event\MovieUnderageEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

#[AsEventListener(MovieUnderageEvent::class)]
class MovieUnderageListener
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function __invoke(MovieUnderageEvent $event): void
    {
        $user = $event->getUser();
        $movie = $event->getMovie();
        $msg = sprintf("User '%s'has tried to view movie '%s' (rated %s)",
            $user->getEmail(),
            $movie->getTitle(),
            $movie->getRated());

        $this->mailer->send(new RawMessage($msg));
    }
}
