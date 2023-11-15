<?php

namespace App\Movie\Search\Provider;

use App\Entity\Genre;
use App\Movie\Search\Mapper\OmdbToGenreMapper;
use App\Repository\GenreRepository;

class GenreProvider implements ProviderInterface
{
    public function __construct(
        private readonly GenreRepository $repository,
        private readonly OmdbToGenreMapper $mapper,
    ) {}

    public function getOne(string $value): Genre
    {
        return $this->repository->findOneBy(['name' => $value])
            ?? $this->mapper->mapValue($value);
    }

    public function getFromOmdbString(string $omdb): iterable
    {
        foreach (explode(', ', $omdb) as $name) {
            yield $this->getOne($name);
        }
    }
}
