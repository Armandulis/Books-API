<?php

namespace App\Tests\Service\OpenLibrary;

use App\Service\OpenLibrary\OpenLibraryClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class OpenLibraryClientTest
 * @covers \App\Service\OpenLibrary\OpenLibraryClient
 */
final class OpenLibraryClientTest extends TestCase
{
    private string $apiBaseUrl;
    private HttpClientInterface&MockObject $httpClient;

    private OpenLibraryClient $openLibraryClient;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->apiBaseUrl = 'https://test.api.openlibrary.org/';
        $this->httpClient = $this->createMock(HttpClientInterface::class);

        // Set up client
        $this->openLibraryClient = new OpenLibraryClient(
            $this->apiBaseUrl,
            $this->httpClient,
        );
    }

    public function testSendRequest(): void
    {
        // Given response from api
        $method = 'GET';
        $url = '/search.json';
        $options = ['query' => ['title' => 'Throne of Glass']];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn('{"docs":[]}');

        // Then we expect to call request with correct arguments
        $this->httpClient->method('request')
            ->with($method, $this->apiBaseUrl . $url, $options)
            ->willReturn($response);

        // When we call sendRequest
        $result = $this->openLibraryClient->sendRequest($method, $url, $options);

        // Then we expect to receive response
        self::assertSame('{"docs":[]}', $result->getContent());
    }
}
