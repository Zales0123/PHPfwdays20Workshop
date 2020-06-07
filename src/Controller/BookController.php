<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class BookController
{
    /** @var ObjectManager */
    private $entityManager;

    /** @var Environment */
    private $twig;

    public function __construct(ObjectManager $entityManager, Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    public function detailsAction(Request $request): Response
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        /** @var Book $book */
        $book = $bookRepository->find($request->attributes->getInt('id'));

        return new Response($this->twig->render('books/details.html.twig', ['book' => $book]));
    }
}
