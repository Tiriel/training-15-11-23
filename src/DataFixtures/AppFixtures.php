<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('me@me.com')
            ->setRoles(['ROLE_USE'])
            ->setBirthday(new \DateTimeImmutable('15-05-2008'));
        $user->setPassword($this->hasher->hashPassword($user, 'abcd1234'));
        $manager->persist($user);

        $manager->flush();
    }
}
