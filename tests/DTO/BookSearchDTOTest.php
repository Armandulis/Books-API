<?php

namespace App\Tests\DTO;

use App\DTO\BookSearchDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class BookSearchDTOTest
 * @covers \App\DTO\BookSearchDTO
 */
final class BookSearchDTOTest extends TestCase
{
    public function testBookSearchDTO(): void
    {
        // Given BookSearchDTO with correct values
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchValue = 'Throne of Glass';
        $bookSearchDTO->searchType = 'title';
        $bookSearchDTO->page = 10;
        $bookSearchDTO->limit = 25;

        // Then we expect correct values to be set
        self::assertSame('Throne of Glass', $bookSearchDTO->searchValue);
        self::assertSame('title', $bookSearchDTO->searchType);
        self::assertSame(10, $bookSearchDTO->page);
        self::assertSame(25, $bookSearchDTO->limit);
    }
}
