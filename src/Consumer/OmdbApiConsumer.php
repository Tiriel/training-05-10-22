<?php

namespace App\Consumer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public const MODE_ID = 'i';
    public const MODE_TITLE = 't';

    public function __construct(
        private HttpClientInterface $omdbClient
    ) {}

    public function getMovie(string $mode, string $value): array
    {
        if (!\in_array($mode, [self::MODE_ID, self::MODE_TITLE])) {
            throw new \RuntimeException();
        }

        $data = $this->omdbClient->request(
            Request::METHOD_GET,
            '',
            ['query' => [$mode => $value]]
        )
        ->toArray();

        if (array_key_exists('Response', $data) && $data['Response'] === 'False') {
            throw new NotFoundHttpException();
        }

        return $data;
    }
}