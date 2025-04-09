<?php

namespace App\Tests\Message;


use App\DTO\BookSearchDTO;
use App\Message\BooksSearchedMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class BooksSearchedMessageTest
 * @covers \App\Message\BooksSearchedMessage
 */
final class BooksSearchedMessageTest extends TestCase
{
    public function testGetBookSearchDTO(): void
    {
        // Given a BookSearchDTO
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = 'title';
        $bookSearchDTO->searchValue = 'PHP Unit Testing';
        $bookSearchDTO->page = 1;
        $bookSearchDTO->limit = 10;

        // Given BooksSearchedMessage with the BookSearchDTO
        $message = new BooksSearchedMessage($bookSearchDTO);

        // Then we expect the getBookSearchDTO return the same BookSearchDTO and attempt number
        self::assertSame($bookSearchDTO, $message->getBookSearchDTO());
        self::assertSame(1, $message->getAttemptNumber());
    }

    public function testGetAttemptNumberWithCustomValue(): void
    {
        // Given a BookSearchDTO
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = 'isbn';
        $bookSearchDTO->searchValue = '978-3-16-148410-0';
        $bookSearchDTO->page = 1;
        $bookSearchDTO->limit = 10;

        // Given BooksSearchedMessage with the BookSearchDTO and attempt number 3
        $message = new BooksSearchedMessage($bookSearchDTO, 3);

        // Then we expect the getBookSearchDTO return the same BookSearchDTO and attempt number
        self::assertSame(3, $message->getAttemptNumber());
    }
}
