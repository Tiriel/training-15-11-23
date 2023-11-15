<?php

namespace App\Movie\Search\Mapper;

use App\Entity\Genre;

class OmdbToGenreMapper implements OmdbMapperInterface
{

    public function mapValue(mixed $value): Genre
    {
        if (!\is_string($value)) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($value);
    }
}
