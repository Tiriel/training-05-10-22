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
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly MovieRepository $repository,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbMovieTransformer $transformer,
        private readonly Security $security
    ) {}

    public function setIo(SymfonyStyle $io): void
    {
        $this->io = $io;
    }

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
        $this->sendIo('text', 'Consuming OMDb');
        $data = $this->consumer->getMovie($mode, $value);

        if ($movieEntity = $this->repository->findOneBy(['title' => $data['Title']])) {
            $this->sendIo('note', 'Movie found in database!');
            return $movieEntity;
        }

        $movie = $this->transformer->transform($data);
        $this->sendIo('text', 'Movie found, saving in database');
        $movie->setCreatedBy($this->security->getUser());
        $this->repository->add($movie, true);

        return $movie;
    }

    private function sendIo(string $type, string $message): void
    {
        if ($this->io instanceof SymfonyStyle && method_exists($this->io, $type)) {
            $this->io->$type($message);
        }
    }
}