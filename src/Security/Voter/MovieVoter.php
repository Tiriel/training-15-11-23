<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Event\MovieUnderageEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MovieVoter extends Voter
{
    public const EDIT = 'movie.edit';
    public const UNDERAGE = 'movie.underage';

    public function __construct(private EventDispatcherInterface $dispatcher) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::EDIT, self::UNDERAGE])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::UNDERAGE => $this->checkUnderage($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };
    }

    private function checkUnderage(Movie $movie, User $user): bool
    {
        $age = $user->getBirthday()?->diff(new \DateTimeImmutable())->y ?? null;

        $vote = match ($movie->getRated()) {
            'G' => true,
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17,
            default => false,
        };

        if (false === $vote) {
            $this->dispatcher->dispatch(new MovieUnderageEvent($movie, $user));
        }

        return $vote;
    }

    private function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkUnderage($movie, $user) && $user === $movie->getCreatedBy();
    }
}
