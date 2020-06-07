<?php

declare(strict_types=1);

use App\Entity\Book;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Webmozart\Assert\Assert;

final class FeatureContext implements Context
{
    /** @var Book */
    private $book;

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
    }

    /**
     * @When I check the details of :book book
     */
    public function iCheckTheDetailsOfBook(string $book): void
    {
        // intentionally left blank, no use of this step in the domain context
    }

    /**
     * @Then I should notice it's written by :author
     */
    public function iShouldNoticeItsWrittenBy(string $author): void
    {
        Assert::same($this->book->getAuthor(), $author);
    }

    /**
     * @Then it should be categories as :genre
     */
    public function itShouldBeCategoriesAs(string $genre): void
    {
        Assert::same($this->book->getGenre(), $genre);
    }

    /**
     * @Then it was released on :releaseDate
     */
    public function itWasReleasedOn(string $releaseDate): void
    {
        Assert::eq($this->book->getReleaseDate(), new \DateTime($releaseDate));
    }
}
