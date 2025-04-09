<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Isbn;
use PHPUnit\Framework\TestCase;

/**
 * Class BookTest
 * @covers \App\Entity\Book
 */
final class BookTest extends TestCase
{
    public function testSetAndGetTitle(): void
    {
        // Given a Book instance
        $book = new Book();

        // When setting the title
        $book->setTitle('Throne of Glass');

        // Then we expect the same title
        self::assertSame('Throne of Glass', $book->getTitle());
    }

    public function testSetAndGetExternalId(): void
    {
        // Given book
        $book = new Book();

        // When we call setExternalId
        $book->setExternalId('OL123M');

        // Then we expect the same external id
        self::assertSame('OL123M', $book->getExternalId());
    }

    public function testSetAndGetFirstPublishYear(): void
    {
        // Given book
        $book = new Book();

        // When we call setFirstPublishYear
        $book->setFirstPublishYear(2012);

        // Then we expect same year
        self::assertSame(2012, $book->getFirstPublishYear());
    }

    public function testGetIdInitiallyNull(): void
    {
        // Given book
        $book = new Book();

        // When we call getId
        // Then we expect to receive null
        self::assertNull($book->getId());
    }

    public function testAddAndRemoveAuthor(): void
    {
        // Given Book
        $book = new Book();
        $author = $this->createMock(Author::class);

        // When adding the author
        $author->expects(self::once())->method('addBook')->with($book);
        $book->addAuthor($author);

        // Then we expect book to have same author
        self::assertTrue($book->getAuthors()->contains($author));

        // When removing the author
        $author->expects(self::once())->method('removeBook')->with($book);
        $book->removeAuthor($author);

        // Then we expect book to not have that author anymore
        self::assertFalse($book->getAuthors()->contains($author));
    }

    public function testReplaceAuthors(): void
    {
        // Given book
        $book = new Book();
        $author1 = $this->createMock(Author::class);
        $author2 = $this->createMock(Author::class);

        // When we call replace authors
        $book->replaceAuthors([$author1, $author2]);

        // Then we expect book to contain all new authors
        self::assertCount(2, $book->getAuthors());
        self::assertTrue($book->getAuthors()->contains($author1));
        self::assertTrue($book->getAuthors()->contains($author2));
    }

    public function testAddAndRemoveIsbn(): void
    {
        // Given book
        $book = new Book();
        $isbn = $this->createMock(Isbn::class);

        // When we call addIsbn
        $isbn->method('setBook')->with($book);
        $book->addIsbn($isbn);

        // Then we expect book to contain new isbn
        self::assertTrue($book->getIsbns()->contains($isbn));

        // When we call remove isbn
        $isbn->method('setBook')->with(null);
        $book->removeIsbn($isbn);

        // Then we expect book to no longer contain new isbn
        self::assertFalse($book->getIsbns()->contains($isbn));
    }

    public function testJsonSerialize(): void
    {
        // Given a Book with title, externalId, and firstPublishYear
        $book = new Book();
        $book->setTitle('Throne of Glass');
        $book->setExternalId('OL123M');
        $book->setFirstPublishYear(2012);

        // And authors
        $author1 = $this->createMock(Author::class);
        $author1->method('getId')->willReturn(1);
        $author1->method('getName')->willReturn('Sarah J. Maas');
        $book->replaceAuthors([$author1]);

        // And ISBN
        $isbn1 = $this->createMock(Isbn::class);
        $isbn1->method('getIsbn')->willReturn('9781234567890');
        $book->addIsbn($isbn1);

        // When serializing to JSON
        $serialized = $book->jsonSerialize();

        // Then we expect the proper structure
        self::assertSame('Throne of Glass', $serialized['title']);
        self::assertSame('OL123M', $serialized['externalId']);
        self::assertSame(2012, $serialized['firstPublishYear']);
        self::assertCount(1, $serialized['authors']);
        self::assertSame(['id' => 1, 'name' => 'Sarah J. Maas'], $serialized['authors'][0]);
        self::assertSame(['9781234567890'], $serialized['isbns']->toArray());
    }

}
