<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\OmdbApiConsumer;
use App\Movie\SearchTypeEnum;
use App\Movie\Transformer\OmdbMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider
{
    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly EntityManagerInterface $manager,
        private readonly OmdbMovieTransformer $movieTransformer,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getMovieByTitle(string $title): Movie
    {
        return $this->getMovie(SearchTypeEnum::Title, $title);
    }

    public function getMovie(SearchTypeEnum $type, string $value): Movie
    {
        $data = $this->consumer->fetchMovie($type, $value);

        if ($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) {
            return $movie;
        }

        $movie = $this->movieTransformer->transform($data);
        foreach ($this->genreProvider->getGenreFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
