<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

#[AutoconfigureTag(name: 'security.voter', attributes: ['priority' => 350])]
class BookVoter extends Voter
{
    public const EDIT = 'book.edit';
    public const SHOW = 'book.show';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::SHOW])
            && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (\in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return true;
        }

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->checkEdit($subject, $user),
            self::SHOW => $this->checkShow($subject, $user),
            default => false,
        };
    }

    private function checkEdit(Book $book, User $user): bool
    {
        return $user === $book->getCreatedBy();
    }

    private function checkShow(Book $book, User $user): bool
    {
        return true;
    }
}
