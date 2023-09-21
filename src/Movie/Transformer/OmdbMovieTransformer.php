<?php

namespace App\Movie\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbMovieTransformer implements DataTransformerInterface
{
    private const KEYS = [
        'Title',
        'Year',
        'Released',
        'Plot',
        'Country',
        'Poster',
        'Genre',
    ];

    public function __construct(private readonly OmdbGenreTransformer $genreTransformer) {}

    public function transform(mixed $value)
    {
        if (!\is_array($value) || \count(\array_diff(self::KEYS, \array_keys($value))) > 0) {
            throw new \InvalidArgumentException();
        }

        $date = $value['Released'] === 'N/A' ? '01-01-'.$value['Year'] : $value['Released'];

        $movie = (new Movie())
            ->setTitle($value['Title'])
            ->setPlot($value['Plot'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPoster($value['Poster'])
            ->setPrice(5.0)
        ;

        foreach (explode(', ', $value['Genre']) as $genreName) {
            $genre = $this->genreTransformer->transform($genreName);
            $movie->addGenre($genre);
        }

        return $movie;
    }

    public function reverseTransform(mixed $value)
    {
        throw new \RuntimeException('Not implemented.');
    }
}
