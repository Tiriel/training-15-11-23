<?php

namespace App\Movie\Search\Provider;

use App\Entity\Genre;

class GenreProvider implements ProviderInterface
{
    public function getOne(string $value): Genre
    {
        // Check if Genre in DB
        //      if yes, return Genre
        //      if no, build Genre and return
    }

    public function getFromOmdbString(string $omdb): iterable
    {
        foreach (explode(', ', $omdb) as $name) {
            yield $this->getOne($name);
        }
    }
}
