<?php

namespace App\Tests\Service;

use App\DTO\BookSearchDTO;
use App\Entity\Book;
use App\Entity\Isbn;
use App\Enum\BookSearchTypeEnum;
use App\Message\BooksSearchedMessage;
use App\Service\BookSearchService;
use App\Service\BookService;
use App\Service\IsbnService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class BookSearchServiceTest
 * @covers \App\Service\BookSearchService
 */
final class BookSearchServiceTest extends TestCase
{
    private BookService&MockObject $bookService;
    private MessageBusInterface&MockObject $messageBus;
    private IsbnService&MockObject $isbnService;

    private BookSearchService $bookSearchService;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->bookService = $this->createMock(BookService::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->isbnService = $this->createMock(IsbnService::class);

        // Set up service
        $this->bookSearchService = new BookSearchService(
            $this->bookService,
            $this->messageBus,
            $this->isbnService
        );
    }

    public function testSearchByTitle(): void
    {
        // Given search dto with search type set to title
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = BookSearchTypeEnum::TITLE->value;
        $bookSearchDTO->searchValue = 'Throne of glass';
        $bookSearchDTO->limit = 10;
        $bookSearchDTO->page = 1;

        // Then we expect to call matchByTitle with correct parameters
        // Given book exists in the database
        $book = $this->createMock(Book::class);
        $books = [$book];
        $this->bookService
            ->expects(self::once())
            ->method('matchByTitle')
            ->with('Throne of glass', 10, 1)
            ->willReturn($books);

        // Then we expect the message to be dispatched
        $this->messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(BooksSearchedMessage::class))
            ->willReturn(Envelope::wrap($bookSearchDTO));

        // When we call search
        $result = $this->bookSearchService->search($bookSearchDTO);

        // Then we expect to get correct books
        self::assertSame($books, $result);
    }

    public function testSearchByIsbn(): void
    {
        // Given search dto with search type set to isbn
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = BookSearchTypeEnum::ISBN->value;
        $bookSearchDTO->searchValue = '1234567890';
        $bookSearchDTO->limit = 10;
        $bookSearchDTO->page = 1;

        // Then we expect to call findOneBy with correct isbn number
        // Given isbn with book exists in the database
        $isbn = $this->createMock(Isbn::class);
        $book = $this->createMock(Book::class);
        $isbn->expects(self::exactly(2))->method('getBook')->willReturn($book);
        $this->isbnService
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['isbn' => '1234567890'])
            ->willReturn($isbn);

        // Expect the message to be dispatched
        $this->messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(BooksSearchedMessage::class))
            ->willReturn(Envelope::wrap($bookSearchDTO));

        // When we call search
        $result = $this->bookSearchService->search($bookSearchDTO);

        // Then we expect to receive correct book
        self::assertSame([$book], $result);
    }

    public function testSearchByAuthor(): void
    {
        // Given search dto with search type set to author
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = BookSearchTypeEnum::AUTHOR->value;
        $bookSearchDTO->searchValue = 'Sarah';
        $bookSearchDTO->limit = 10;
        $bookSearchDTO->page = 1;

        // Then we expect to call matchByAuthorName once with correct arguments
        // Given Book exists in the database
        $book = $this->createMock(Book::class);
        $this->bookService
            ->expects(self::once())
            ->method('matchByAuthorName')
            ->with('Sarah', 10, 1)
            ->willReturn([$book]);

        // Then we expect the message to be dispatched
        $this->messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(BooksSearchedMessage::class))
            ->willReturn(Envelope::wrap($bookSearchDTO));

        // When we call search
        $result = $this->bookSearchService->search($bookSearchDTO);

        // Then we expect to receive correct books
        self::assertSame([$book], $result);
    }

    public function testSearchWithInvalidType(): void
    {
        // Given search dto with search type set invalid
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = 'invalid';
        $bookSearchDTO->searchValue = 'Invalid Search';
        $bookSearchDTO->limit = 10;
        $bookSearchDTO->page = 1;

        // Expect the message to be never dispatched
        $this->messageBus
            ->expects(self::never())
            ->method('dispatch');

        // When we call search
        $result = $this->bookSearchService->search($bookSearchDTO);

        // Then we expect no results
        self::assertEmpty($result);
    }
}
