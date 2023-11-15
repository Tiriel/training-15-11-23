<?php

namespace App\Movie\Search\Consumer;

use App\Movie\Search\Enum\SearchType;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[When(env: 'prod')]
#[AsDecorator(decorates: OmdbApiConsumer::class)]
class CacheableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        private readonly OmdbApiConsumer $inner,
        private readonly CacheInterface $cache,
        private readonly SluggerInterface $slugger,
    ) {}

    public function fetch(SearchType $type, string $value): array
    {
        $key = $this->slugger->slug(sprintf("%s-%s", $type->value, $value));

        return $this->cache->get(
            $key,
            fn($key) => $this->inner->fetch($type, $value)
        );
    }
}
