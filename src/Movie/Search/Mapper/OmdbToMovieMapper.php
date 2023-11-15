<?php

namespace App\Movie\Search\Mapper;

use App\Entity\Movie;

class OmdbToMovieMapper implements OmdbMapperInterface
{
    public const KEYS = [
        'Title',
        'Year',
        'Released',
        'Poster',
        'Country',
        'Plot',
        'imdbID',
        'Rated',
    ];

    public function mapValue(mixed $value): Movie
    {
        if (!\is_array($value)
            || \count(array_diff(self::KEYS, array_keys($value))) > 0
        ) {
            throw new \InvalidArgumentException();
        }
        // TODO: Implement mapValue() method.
    }
}
