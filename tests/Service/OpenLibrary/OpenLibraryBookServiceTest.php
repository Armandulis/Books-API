<?php

namespace App\Tests\Service\OpenLibrary;

use App\DTO\OpenLibraryBookDTO;
use App\Factory\BookFactory;
use App\Service\OpenLibrary\OpenLibraryBookService;
use App\Service\OpenLibrary\OpenLibraryClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class OpenLibraryBookServiceTest
 * @covers \App\Service\OpenLibrary\OpenLibraryBookService
 */
final class OpenLibraryBookServiceTest extends TestCase
{
    private OpenLibraryClient&MockObject $openLibraryClient;
    private BookFactory&MockObject $bookFactory;

    private OpenLibraryBookService $openLibraryBookService;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->openLibraryClient = $this->createMock(OpenLibraryClient::class);
        $this->bookFactory = $this->createMock(BookFactory::class);

        // Set up service
        $this->openLibraryBookService = new OpenLibraryBookService(
            $this->openLibraryClient,
            $this->bookFactory,
        );
    }

    public function testGetBooks(): void
    {
        // Given response from openAPI
        $searchQuery = ['title' => 'Throne of glass'];
        $responseContent = json_encode([
            'docs' => [
                [
                    'author_key' => ['29921'],
                    'title' => 'Throne of Glass',
                ]
            ]
        ]);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getContent')->willReturn($responseContent);

        // Then we expect to call send request with correct arguments
        $this->openLibraryClient
            ->expects(self::once())
            ->method('sendRequest')
            ->with('GET', '/search.json', ['title' => 'Throne of glass'])
            ->willReturn($mockResponse);

        // Given book dto
        $openLibraryBookDTO = new OpenLibraryBookDTO();
        $this->bookFactory
            ->method('openLibraryBookDTOFromResponse')
            ->willReturn($openLibraryBookDTO);

        // When we call getBooks
        $result = $this->openLibraryBookService->getBooks($searchQuery);

        // Then we expect to receive correct books
        self::assertSame([$openLibraryBookDTO], $result);
    }
}
