<?php

namespace App\Movie\Provider;

use App\Entity\Genre;
use App\Movie\Transformer\OmdbGenreTransformer;
use App\Repository\GenreRepository;

class GenreProvider
{
    public function __construct(
        private readonly GenreRepository $repository,
        private readonly OmdbGenreTransformer $transformer
    ) {}

    public function getOneGenre(string $name): Genre
    {
        return $this->repository->findOneBy(['name' => $name]) ?? $this->transformer->transform($name);
    }

    public function getGenreFromOmdbString(string $omdb): \Generator
    {
        foreach (explode(', ', $omdb) as $name) {
            yield $this->getOneGenre($name);
        }
    }
}
