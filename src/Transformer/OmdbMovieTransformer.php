<?php

namespace App\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbMovieTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly GenreRepository $genreRepository
    ) {}

    public function transform($value): Movie
    {
        $date = $value['Released'] === 'N/A' ? $value['Year'] : $value['Released'];
        $genreNames = explode(', ', $value['Genre']);

        $movie = (new Movie())
            ->setTitle($value['Title'])
            ->setPoster($value['Poster'])
            ->setCountry($value['Country'])
            ->setPrice(5.0)
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setRated($value['Rated'])
            ->setImdbId($value['imdbID'])
            ;

        foreach ($genreNames as $genreName) {
            $genre = $this->genreRepository->findOneBy(['name' => $genreName])
                ?? (new Genre())->setName($genreName);
            $movie->addGenre($genre);
        }

        return $movie;
    }

    public function reverseTransform($value)
    {
        throw new \LogicException("Not implemented.");
    }
}