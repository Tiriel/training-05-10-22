<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/{id<\d+>?1}', name: 'show', methods: ['GET'])]
//    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => $id,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(): Response
    {
        $book = new Book();
        $bookForm = $this->createForm(BookType::class, $book);

        return $this->renderForm('book/new.html.twig', [
            'bookForm' => $bookForm,
        ]);
    }
}
