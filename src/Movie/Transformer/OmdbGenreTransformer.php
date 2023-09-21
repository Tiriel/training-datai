<?php

namespace App\Movie\Transformer;

use App\Entity\Genre;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbGenreTransformer implements DataTransformerInterface
{
    public function transform(mixed $value)
    {
        if (!\is_string($value) || str_contains($value, ',')) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($value);
    }

    public function reverseTransform(mixed $value)
    {
        throw new \RuntimeException('Not implemented.');
    }
}
