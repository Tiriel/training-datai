<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\OmdbApiConsumer;
use App\Movie\SearchTypeEnum;
use App\Movie\Transformer\OmdbMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly EntityManagerInterface $manager,
        private readonly OmdbMovieTransformer $movieTransformer,
        private readonly GenreProvider $genreProvider,
        private readonly Security $security,
    ) {}

    public function getMovieByTitle(string $title): Movie
    {
        return $this->getMovie(SearchTypeEnum::Title, $title);
    }

    public function getMovie(SearchTypeEnum $type, string $value): Movie
    {
        $this->io?->text('Checking on OMDbApi');
        $data = $this->consumer->fetchMovie($type, $value);

        if ($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) {
            $this->io?->note('Movie already in database!');
            return $movie;
        }

        $this->io?->text('Creating movie object.');
        $movie = $this->movieTransformer->transform($data);
        foreach ($this->genreProvider->getGenreFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        if (($user = $this->security->getUser()) instanceof User) {
            $movie->setCreatedBy($user);
        }

        $this->io?->note('Saving movie in database!');
        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }
}
