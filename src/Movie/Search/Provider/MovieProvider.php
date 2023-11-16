<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\Consumer\OmdbApiConsumer;
use App\Movie\Search\Enum\SearchType;
use App\Movie\Search\Mapper\OmdbToMovieMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider implements ProviderInterface
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly EntityManagerInterface $manager,
        private readonly OmdbToMovieMapper $mapper,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getOne(string $value, SearchType $type = SearchType::Title): Movie
    {
        $this->io?->text('Fetching informations from OMDb');
        $data = $this->consumer->fetch($type, $value);

        if (($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) instanceof Movie) {
            $this->io?->note('Movie already in database!');

            return $movie;
        }

        $movie = $this->mapper->mapValue($data);
        $this->io?->text('Creating Movie object...');

        foreach ($this->genreProvider->getFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->io?->text('Saving Movie ine database...');
        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }
}
