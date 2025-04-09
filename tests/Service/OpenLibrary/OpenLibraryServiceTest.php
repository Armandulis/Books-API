<?php

namespace App\Tests\Service\OpenLibrary;

use App\DTO\BookSearchDTO;
use App\DTO\OpenLibraryBookDTO;
use App\Factory\BookFactory;
use App\Service\OpenLibrary\OpenLibraryBookService;
use App\Service\OpenLibrary\OpenLibraryService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class OpenLibraryServiceTest
 * @covers \App\Service\OpenLibrary\OpenLibraryService
 */
final class OpenLibraryServiceTest extends TestCase
{
    private OpenLibraryBookService&MockObject $openLibraryBookService;
    private BookFactory&MockObject $bookFactory;

    private OpenLibraryService $openLibraryService;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->openLibraryBookService = $this->createMock(OpenLibraryBookService::class);
        $this->bookFactory = $this->createMock(BookFactory::class);

        // Set up service
        $this->openLibraryService = new OpenLibraryService(
            $this->openLibraryBookService,
            $this->bookFactory
        );
    }

    public function testSearchBooks(): void
    {
        // Given a BookSearchDTO
        $bookSearchDTO = $this->createMock(BookSearchDTO::class);
        $bookSearchQuery = ['query' => ['title' => 'Throne of Glass']];
        $this->bookFactory->method('openLibraryBookSearchQueryFromSearchDTO')
            ->with($bookSearchDTO)
            ->willReturn($bookSearchQuery);

        // Given the response from openLibraryBookService
        $bookDTO = $this->createMock(OpenLibraryBookDTO::class);
        $booksDTOs = [$bookDTO];

        // Then we expect to call getBooks with the correct query
        $this->openLibraryBookService->method('getBooks')
            ->with($bookSearchQuery)
            ->willReturn($booksDTOs);

        // When we call searchBooks
        $result = $this->openLibraryService->searchBooks($bookSearchDTO);

        // Then we expect to receive the correct result
        self::assertSame($booksDTOs, $result);
    }
}
