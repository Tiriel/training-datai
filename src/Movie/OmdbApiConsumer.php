<?php

namespace App\Movie;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public function __construct(
        private readonly HttpClientInterface $omdbClient
    ) {}

    public function fetchMovie(SearchTypeEnum $type, string $value): array
    {
        $data = $this->omdbClient->request(
            'GET',
            '',
            ['query' => [$type->value => $value]]
        )->toArray();

        if (array_key_exists('Error', $data) && $data['Error'] === 'Movie not found!') {
            throw new NotFoundHttpException('Movie not found!');
        }

        return $data;
    }
}
