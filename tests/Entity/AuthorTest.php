<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthorTest
 * @covers \App\Entity\Author
 */
final class AuthorTest extends TestCase
{
    public function testSetAndGetExternalId(): void
    {
        // Given author
        $author = new Author();

        // When we call setExternalId
        $author->setExternalId('A123');

        // Then we expect to receive same external id
        self::assertSame('A123', $author->getExternalId());
    }

    public function testSetAndGetName(): void
    {
        // Given author
        $author = new Author();

        // When we call setName
        $author->setName('Sarah J. Maas');

        // Then we expect to receive same name
        self::assertSame('Sarah J. Maas', $author->getName());
    }

    public function testAddBook(): void
    {
        // Given author and book
        $author = new Author();
        $book = $this->createMock(Book::class);

        // When we call addBook
        $author->addBook($book);

        // Then we expect author to have that book
        self::assertTrue($author->getBooks()->contains($book));
    }

    public function testAddBookDoesNotDuplicate(): void
    {
        // Given author and book
        $author = new Author();
        $book = $this->createMock(Book::class);

        // When we call add Book
        $author->addBook($book);
        $author->addBook($book);

        // Then we expect to contain 1 book - no duplicates
        self::assertCount(1, $author->getBooks());
    }

    public function testRemoveBook(): void
    {
        // Given author and book
        $author = new Author();
        $book = $this->createMock(Book::class);

        // When we call remove book
        $author->addBook($book);
        $author->removeBook($book);

        // Then we expect author to not contain that book
        self::assertFalse($author->getBooks()->contains($book));
    }

    public function testGetIdInitiallyNull(): void
    {
        // Given author
        $author = new Author();

        // Then its ID should initially be null
        self::assertNull($author->getId());
    }
}
