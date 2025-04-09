<?php

namespace App\Tests\Controller;

use App\Controller\BookController;
use App\DTO\BookSearchDTO;
use App\Entity\Book;
use App\Service\BookSearchService;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookControllerTest
 * @covers \App\Controller\BookController
 */
final class BookControllerTest extends TestCase
{
    private BookController $bookController;
    private BookSearchService&MockObject $bookSearchService;
    public Request $request;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->request = new Request();
        $this->bookSearchService = $this->createMock(BookSearchService::class);

        // Set up controller
        $this->bookController = new BookController(
            $this->bookSearchService
        );

        $container = $this->createMock(ContainerInterface::class);
        $this->bookController->setContainer($container);
    }

    public function testMissingSearchType(): void
    {
        // Given user didn't provide search type but provided searchValue
        $this->request->query->set('paieska', 'Throne of glass');

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive error response
        self::assertSame(400, json_decode($response->getContent(), true)['code']);
        self::assertSame('Nera paieskos tipo', json_decode($response->getContent(), true)['message']);
    }

    public function testInvalidSearchType(): void
    {
        // Given user provided search type but it's invalid
        $this->request->query->set('paieskosTipas', 'country');

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive error response
        self::assertSame(400, json_decode($response->getContent(), true)['code']);
        self::assertSame('Nera paieskos tipo', json_decode($response->getContent(), true)['message']);
    }

    public function testMissingSearchValue(): void
    {
        // Given user provided search type but no search value
        $this->request->query->set('paieskosTipas', 'title');

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive error response
        self::assertSame(400, json_decode($response->getContent(), true)['code']);
        self::assertSame('Nera paieskos', json_decode($response->getContent(), true)['message']);
    }

    public function testUnknownError(): void
    {
        // Given user provided search type and value
        $this->request->query->set('paieskosTipas', 'title');
        $this->request->query->set('paieska', 'Throne of glass');

        // Given exception is thrown when we try to search
        $this->bookSearchService->method('search')->willThrowException(new Exception('Database down'));

        // When we execute searchBooks endpoint
        $response = $this->bookController->searchBooks($this->request);

        // Then we expect to receive error response
        self::assertSame(500, json_decode($response->getContent(), true)['code']);
        self::assertSame('Kazkas nutiko', json_decode($response->getContent(), true)['message']);
    }

    public function testSearchBooks(): void
    {
        // Given user provided search type and value
        $this->request->query->set('paieskosTipas', 'title');
        $this->request->query->set('paieska', 'Hitchhikers guide to the galaxy');

        $book = $this->createMock(Book::class);
        $book->method('jsonSerialize')->willReturn(
            [
                'id' => 42,
                'title' => 'Hitchhikers guide to the galaxy'
            ]
        );

        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = 'title';
        $bookSearchDTO->searchValue = 'Hitchhikers guide to the galaxy';

        // Then we expect to search books once with correct search dto
        // Given there are books based on search
        $this->bookSearchService->expects(self::once())
            ->method('search')
            ->with($bookSearchDTO)
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
