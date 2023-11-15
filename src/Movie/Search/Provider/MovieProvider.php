<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\Consumer\OmdbApiConsumer;
use App\Movie\Search\Enum\SearchType;
use App\Movie\Search\Mapper\OmdbToMovieMapper;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider implements ProviderInterface
{
    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly EntityManagerInterface $manager,
        private readonly OmdbToMovieMapper $mapper,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getOne(string $value, SearchType $type = SearchType::Title): Movie
    {
        $data = $this->consumer->fetch($type, $value);

        if (($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) instanceof Movie) {
            return $movie;
        }

        $movie = $this->mapper->mapValue($data);

        foreach ($this->genreProvider->getFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
