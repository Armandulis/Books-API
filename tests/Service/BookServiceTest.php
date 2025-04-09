<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\BookService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class BookServiceTest
 * @covers \App\Service\BookService
 */
final class BookServiceTest extends TestCase
{
    private BookRepository&MockObject $bookRepository;

    private BookService $bookService;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->bookRepository = $this->createMock(BookRepository::class);

        // Set up service
        $this->bookService = new BookService($this->bookRepository);
    }

    public function testFindOneBy(): void
    {
        // Given book entity and criteria
        $book = new Book();
        $book->setTitle('Sample Book');
        $criteria = ['title' => 'Sample Book'];

        // Then we expect the repository's findOneBy method to be called with the criteria
        // Given there is book in the database
        $this->bookRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with($criteria)
            ->willReturn($book);

        // When we call findOneBy
        $result = $this->bookService->findOneBy($criteria);

        // Then the result should be the same Book
        self::assertSame($book, $result);
    }

    public function testFindOneByReturnsNullWhenNotFound(): void
    {
        // Given criteria that will not match any book
        $criteria = ['title' => 'Non-existent Book'];

        // Then we expect the repository's findOneBy method to be called
        // Given it returns null
        $this->bookRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with($criteria)
            ->willReturn(null);

        // When calling the findOneBy
        $result = $this->bookService->findOneBy($criteria);

        // Then the result should be null
        self::assertNull($result);
    }

    public function testSave(): void
    {
        // Given a book
        $book = new Book();
        $book->setTitle('Sample Book');

        // Then we expect the repository's save method to be called with the Book entity
        $this->bookRepository
            ->expects(self::once())
            ->method('save')
            ->with($book);

        // When we call save
        $this->bookService->save($book);
    }

    public function testMatchByTitle(): void
    {
        // Given a book entity and search parameters
        $book = new Book();
        $book->setTitle('Sample Book');
        $searchValue = 'Sample Book';
        $limit = 10;
        $page = 1;
        $books = [$book];

        // Then we expect the repository's matchByTitle method to be called with the search parameters
        // Given book is in the database
        $this->bookRepository
            ->expects(self::once())
            ->method('matchByTitle')
            ->with($searchValue, $limit, 0)
            ->willReturn($books);

        // When we call matchByTitle
        $result = $this->bookService->matchByTitle($searchValue, $limit, $page);

        // Then the result should be the same books array
        self::assertSame($books, $result);
    }

    public function testMatchByAuthorName(): void
    {
        // Given book entity and search parameters
        $book = new Book();
        $book->setTitle('Sample Book');
        $searchValue = 'Sample Author';
        $limit = 10;
        $page = 1;
        $books = [$book];

        // Then we expect the repository's matchByAuthorName method to be called with the search parameters
        // Given Book is in the database
        $this->bookRepository
            ->expects(self::once())
            ->method('matchByAuthorName')
            ->with($searchValue, $limit, 0)
            ->willReturn($books);

        // When we call the matchByAuthorName
        $result = $this->bookService->matchByAuthorName($searchValue, $limit, $page);

        // Then the result should be the same books array
        self::assertSame($books, $result);
    }
}