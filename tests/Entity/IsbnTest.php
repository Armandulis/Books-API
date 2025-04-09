<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Entity\Isbn;
use PHPUnit\Framework\TestCase;

/**
 * Class Isbn
 * @covers \App\Entity\Isbn
 */
final class IsbnTest extends TestCase
{
    public function testSetAndGetIsbn(): void
    {
        // Given an Isbn instance
        $isbn = new Isbn();

        // When setting an ISBN
        $isbn->setIsbn('9781234567890');

        // Then we expect to retrieve the same ISBN
        self::assertSame('9781234567890', $isbn->getIsbn());
    }

    public function testSetAndGetBook(): void
    {
        // Given an Isbn instance and a mock Book
        $isbn = new Isbn();
        $book = $this->createMock(Book::class);

        // When assigning the book
        $isbn->setBook($book);

        // Then we expect to retrieve the same book
        self::assertSame($book, $isbn->getBook());
    }

    public function testGetIdInitiallyNull(): void
    {
        // Given a new Isbn instance
        $isbn = new Isbn();

        // Then its ID should initially be null
        self::assertNull($isbn->getId());
    }
}
