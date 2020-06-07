<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Book;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Session;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final class FeatureContext implements Context
{
    /** @var Book */
    private $book;

    /** @var ObjectManager */
    private $bookManager;

    /** @var Session */
    private $session;

    /** @var RouterInterface */
    private $router;

    public function __construct(ObjectManager $bookManager, Session $session, RouterInterface $router)
    {
        $this->bookManager = $bookManager;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Given there is a book :title by :author
     */
    public function thereIsABook(string $title, string $author): void
    {
        $this->book = new Book();
        $this->book->setTitle($title);
        $this->book->setAuthor($author);
    }

    /**
     * @Given its release date is :releaseDate
     */
    public function itsReleaseDateIs(string $releaseDate): void
    {
        $this->book->setReleaseDate(new \DateTime($releaseDate));
    }

    /**
     * @Given its genre is :genre
     */
    public function itsGenreIs(string $genre): void
    {
        $this->book->setGenre($genre);

        $this->bookManager->persist($this->book);
        $this->bookManager->flush();
    }

    /**
     * @When I check the details of :title book
     */
    public function iCheckTheDetailsOfBook(string $title): void
    {
        $bookRepository = $this->bookManager->getRepository(Book::class);
        /** @var Book $book */
        $book = $bookRepository->findOneBy(['title' => $title]);

        $this->session->visit($this->router->generate('app_book_details', ['id' => $book->getId()]));
    }

    /**
     * @Then I should notice it's written by :author
     */
    public function iShouldNoticeItsWrittenBy(string $author): void
    {
        Assert::same($this->session->getPage()->find('css', '#author')->getText(), $author);
    }

    /**
     * @Then it should be categories as :genre
     */
    public function itShouldBeCategoriesAs(string $genre): void
    {
        Assert::same($this->session->getPage()->find('css', '#genre')->getText(), $genre);
    }

    /**
     * @Then it was released on :releaseDate
     */
    public function itWasReleasedOn(string $releaseDate): void
    {
        Assert::same($this->session->getPage()->find('css', '#release-date')->getText(), $releaseDate);
    }
}
