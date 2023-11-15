<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;

class MovieProvider implements ProviderInterface
{
    public function getOne(string $value): Movie
    {
        // get data from OMDb
        // Check if title in database
        //      if yes, return movie
        //      if no
        // build Movie object
        // add Genre objects
        // save in DB
        // return Movie
    }
}
