<?php

namespace App\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookManager
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly int $itemsPerPage,
    ) {}

    public function getOneByTitle(string $title): Book
    {
        return $this->manager->getRepository(Book::class)->findOneBy(['title' => $title]);
    }

    public function getPaginated(int $offset)
    {
        return $this->manager->getRepository(Book::class)->findBy([], [], $this->itemsPerPage, $offset);
    }
}
