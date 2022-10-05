<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Security;

class MovieProvider
{
    public function __construct(
        private readonly MovieRepository $repository,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbMovieTransformer $transformer
    ) {}

    public function getMovieById(string $id): Movie
    {
        return $this->getMovie(OmdbApiConsumer::MODE_ID, $id);
    }

    public function getMovieByTitle(string $title): Movie
    {
        return $this->getMovie(OmdbApiConsumer::MODE_TITLE, $title);
    }

    private function getMovie(string $mode, string $value): Movie
    {
        $data = $this->consumer->getMovie($mode, $value);

        if ($movieEntity = $this->repository->findOneBy(['title' => $data['Title']])) {
            return $movieEntity;
        }

        $movie = $this->transformer->transform($data);
        $this->repository->add($movie, true);

        return $movie;
    }
}