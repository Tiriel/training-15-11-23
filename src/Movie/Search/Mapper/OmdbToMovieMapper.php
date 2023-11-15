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

        $date = $value['Released'] === 'N/A' ? '01-01-'.$value['Year'] : $value['Released'];

        return (new Movie())
            ->setTitle($value['Title'])
            ->setPlot($value['Plot'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPoster($value['Poster'])
            ->setPrice(5.0)
            ->setRated($value['Rated'])
            ->setImdbId($value['imdbID'])
        ;
    }
}
