<?php

namespace App\Service\OpenLibrary;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class OpenLibraryClient
 */
class OpenLibraryClient
{
    /**
     * OpenLibraryClient constructor
     * @param string $openLibraryAPIBaseUrl
     * @param HttpClientInterface $httpClient
     */
    public function __construct(
        private readonly string              $openLibraryAPIBaseUrl,
        private readonly HttpClientInterface $httpClient
    )
    {
    }

    /**
     * Sends a custom request to OpenLibrary API
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function sendRequest(string $method, string $url, array $options): ResponseInterface
    {
        return $this->httpClient->request($method, $this->openLibraryAPIBaseUrl . $url, $options);
    }
}