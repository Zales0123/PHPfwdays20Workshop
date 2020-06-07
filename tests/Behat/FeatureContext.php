<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Book;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Session;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final class FeatureContext implements Context
{
    /** @var Book */
    private $book;

    /** @var EntityManagerInterface */
    private $bookManager;

    /** @var Session */
    private $session;

    /** @var RouterInterface */
    private $router;

    /** @var Generator */
    private $faker;

    public function __construct(EntityManagerInterface $bookManager, Session $session, RouterInterface $router)
    {
        $this->bookManager = $bookManager;
        $this->session = $session;
        $this->router = $router;
        $this->faker = Factory::create();
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase(): void
    {
        $this->bookManager->getConnection()->getConfiguration()->setSQLLogger(null);
        (new ORMPurger($this->bookManager))->purge();
        $this->bookManager->clear();
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
     * @Given there are :count more books
     */
    public function thereAreMoreBooks(int $count): void
    {
        $genres = ['Fantasy', 'Sci-fi', 'Adventure', 'Horror'];

        for ($i = 0; $i < $count; $i++) {
            $book = new Book();
            $book->setTitle($this->faker->sentence(3));
            $book->setAuthor($this->faker->firstName.' '.$this->faker->lastName);
            $book->setGenre($genres[array_rand($genres)]);
            $book->setReleaseDate($this->faker->dateTimeBetween('-100 years', '-1 year'));

            $this->bookManager->persist($book);
        }

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
     * @When I browse books index
     */
    public function iBrowseBooksIndex(): void
    {
        $this->session->visit($this->router->generate('app_book_index'));
    }

    /**
     * @Then I should have information about :count books
     */
    public function iShouldHaveInformationAboutBooks(int $count): void
    {
        Assert::eq(
            count($this->session->getPage()->findAll('css', 'table#books tbody tr')),
            $count
        );
    }

    /**
     * @Then one of them should be written by :author
     */
    public function oneOfThemShouldBeWrittenBy(string $author): void
    {
        Assert::notNull($this->session->getPage()->find('css', sprintf('tr:contains("%s")', $author)));
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
