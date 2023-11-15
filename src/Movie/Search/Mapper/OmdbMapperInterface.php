<?php

namespace App\Movie\Search\Mapper;

interface OmdbMapperInterface
{
    public function mapValue(mixed $value): object;
}
