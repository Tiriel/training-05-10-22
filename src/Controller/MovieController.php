<?php

namespace App\Controller;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie', name: 'app_movie_')]
class MovieController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(int $moviesPerPage, string $sfVersion): Response
    {
        dump($moviesPerPage, $sfVersion);
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'Movie Index',
        ]);
    }

    #[Route('/{!id<\d+>?1}', name: 'details')]
    public function details(Movie $movie): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'omdb')]
    public function omdb(string $title, OmdbApiConsumer $consumer)
    {
        dump($consumer->getMovie(OmdbApiConsumer::MODE_TITLE, $title));
        return $this->render('movie/details.html.twig', [
            'movie' => new Movie(),
        ]);
    }
}
