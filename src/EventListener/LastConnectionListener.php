<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

#[AsEventListener(SecurityEvents::INTERACTIVE_LOGIN)]
class LastConnectionListener
{
    public function __construct(private readonly EntityManagerInterface $manager) {}

    public function __invoke(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof User) {
            $user->setLastConnectedAt(new \DateTimeImmutable());

            $this->manager->persist($user);
            $this->manager->flush();
        }
    }
}
