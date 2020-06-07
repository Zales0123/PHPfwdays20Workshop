<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class BookController
{
    /** @var Environment */
    private $twig;

    /** @var ObjectRepository */
    private $bookRepository;

    public function __construct(ObjectManager $entityManager, Environment $twig)
    {
        $this->bookRepository = $entityManager->getRepository(Book::class);
        $this->twig = $twig;
    }

    public function indexAction(): Response
    {
        $books = $this->bookRepository->findBy([], [], 10);

        return new Response($this->twig->render('books/index.html.twig', ['books' => $books]));
    }

    public function detailsAction(Request $request): Response
    {
        /** @var Book $book */
        $book = $this->bookRepository->find($request->attributes->getInt('id'));

        return new Response($this->twig->render('books/details.html.twig', ['book' => $book]));
    }
}
