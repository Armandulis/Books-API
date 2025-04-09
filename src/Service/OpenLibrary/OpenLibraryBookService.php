<?php

namespace App\Service\OpenLibrary;

use App\DTO\OpenLibraryBookDTO;
use App\Factory\BookFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class OpenLibraryBookService
 */
class OpenLibraryBookService
{
    public const BOOK_ENDPOINT = '/search.json';

    /**
     * OpenLibraryBookService constructor
     * @param OpenLibraryClient $openLibraryClient
     * @param BookFactory $BookFactory
     */
    public function __construct(
        private readonly OpenLibraryClient $openLibraryClient,
        private readonly BookFactory       $BookFactory
    )
    {
    }

    /**
     * Gets books from openLibrary API and returns DTO instances of results
     * @param array $searchQuery
     * @return array<OpenLibraryBookDTO>
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBooks(array $searchQuery): array
    {
        $response = $this->openLibraryClient->sendRequest(Request::METHOD_GET, self::BOOK_ENDPOINT, $searchQuery);
        $content = $response->getContent();
        $responseData = json_decode($content, true);
        $bookDTOs = [];
        foreach ($responseData['docs'] as $bookData) {
            $bookDTOs[] = $this->BookFactory->openLibraryBookDTOFromResponse($bookData);
        }

        return $bookDTOs;
    }
}