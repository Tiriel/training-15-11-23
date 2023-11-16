<?php

namespace App\Movie\Event;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

abstract class MovieEvent extends Event
{
    public function __construct(
        private Movie $movie,
        private User $user,
    ) {}

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): MovieEvent
    {
        $this->movie = $movie;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): MovieEvent
    {
        $this->user = $user;
        return $this;
    }
}
