<?php

namespace App\Tests\Controller\V2;

use App\Controller\V2\BookController;
use App\DTO\BookSearchDTO;
use App\Entity\Book;
use App\Factory\BookFactory;
use App\Service\BookSearchService;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * BookControllerTest
 * @covers \App\Controller\V2\BookController
 */
final class BookControllerTest extends TestCase
{
    private BookController $bookController;
    private BookSearchService&MockObject $bookSearchService;
    private BookFactory&MockObject $bookFactory;
    public Request $request;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->request = new Request();
        $this->bookSearchService = $this->createMock(BookSearchService::class);
        $this->bookFactory = $this->createMock(BookFactory::class);

        // Set up controller
        $this->bookController = new BookController(
            $this->bookSearchService,
            $this->bookFactory
        );

        $container = $this->createMock(ContainerInterface::class);
        $this->bookController->setContainer($container);
    }

    public function testMissingSearchType(): void
    {
        // Given user didn't provide search type but provided searchValue
        $this->request->query->set('searchValue', 'Throne of glass');

        // Given we create searchDto
        $searchDto = new BookSearchDto();
        $this->bookFactory->method('searchDTOFromSearchRequest')->willReturn($searchDto);

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive error response
        self::assertSame(400, json_decode($response->getContent(), true)['code']);
        self::assertSame('Missing search type', json_decode($response->getContent(), true)['message']);
    }

    public function testInvalidSearchType(): void
    {
        // Given user provided search type but it's invalid
        $this->request->query->set('searchType', 'country');

        // Given we create searchDto
        $searchDto = new BookSearchDto();
        $searchDto->searchType = 'country';
        $this->bookFactory->method('searchDTOFromSearchRequest')->willReturn($searchDto);

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive error response
        self::assertSame(400, json_decode($response->getContent(), true)['code']);
        self::assertSame('Missing search type', json_decode($response->getContent(), true)['message']);
    }

    public function testMissingSearchValue(): void
    {
        // Given user provided search type but no search value
        $this->request->query->set('searchType', 'title');

        // Given we create searchDto
        $searchDto = new BookSearchDto();
        $searchDto->searchType = 'title';
        $this->bookFactory->method('searchDTOFromSearchRequest')->willReturn($searchDto);

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive error response
        self::assertSame(400, json_decode($response->getContent(), true)['code']);
        self::assertSame('Missing search value', json_decode($response->getContent(), true)['message']);
    }

    public function testUnknownError(): void
    {
        // Given user provided search type and value
        $this->request->query->set('searchType', 'title');
        $this->request->query->set('searchValue', 'Throne of glass');

        // Given we create searchDto
        $searchDto = new BookSearchDto();
        $searchDto->searchType = 'title';
        $searchDto->searchValue = 'Throne of glass';
        $this->bookFactory->method('searchDTOFromSearchRequest')->willReturn($searchDto);

        // Given exception is thrown when we try to search
        $this->bookSearchService->method('search')->willThrowException(new Exception('Database down'));

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive error response
        self::assertSame(500, json_decode($response->getContent(), true)['code']);
        self::assertSame('Could not process the request', json_decode($response->getContent(), true)['message']);
    }

    public function testSearchBooks(): void
    {
        // Given user provided search type and value
        $this->request->query->set('searchType', 'title');
        $this->request->query->set('searchValue', 'Hitchhikers guide to the galaxy');

        // Given we create searchDto
        $searchDto = new BookSearchDto();
        $searchDto->searchType = 'title';
        $searchDto->searchValue = 'Hitchhikers guide to the galaxy';
        $this->bookFactory->method('searchDTOFromSearchRequest')->willReturn($searchDto);

        $book = $this->createMock(Book::class);
        $book->method('jsonSerialize')->willReturn(
            [
                'id' => 42,
                'title' => 'Hitchhikers guide to the galaxy'
            ]
        );

        // Then we expect to search books once with correct search dto
        // Given there are books based on search
        $this->bookSearchService->expects(self::once())
            ->method('search')
            ->with($searchDto)
            ->willReturn([$book]);

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive correct response with books
        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            [
                [
                    'id' => 42,
                    'title' => 'Hitchhikers guide to the galaxy'
                ]
            ],
            json_decode($response->getContent(), true)['data']
        );
    }
}
