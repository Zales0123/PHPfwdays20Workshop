<?php

namespace spec\App\Entity;

use App\Entity\Book;
use PhpSpec\ObjectBehavior;

final class BookSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Book::class);
    }

    function it_has_title(): void
    {
        $this->setTitle('Game of Thrones');
        $this->getTitle()->shouldReturn('Game of Thrones');
    }

    function it_has_author(): void
    {
        $this->setAuthor('George R.R. Martin');
        $this->getAuthor()->shouldReturn('George R.R. Martin');
    }

    function it_has_genre(): void
    {
        $this->setGenre('Fantasy');
        $this->getGenre()->shouldReturn('Fantasy');
    }

    function it_has_release_date(): void
    {
        $this->setReleaseDate(new \DateTime('01/08/1996'));
        $this->getReleaseDate()->shouldBeLike(new \DateTime('01/08/1996'));
    }
}
