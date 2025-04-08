<?php

namespace App\Service\OpenLibrary;

use App\DTO\OpenLibraryBookDTO;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OpenLibraryBookService
 */
class OpenLibraryBookService
{
    public const BOOK_ENDPOINT = '/search.json';

    /**
     * OpenLibraryBookService constructor
     * @param OpenLibraryClient $openLibraryClient
     */
    public function __construct(private readonly OpenLibraryClient $openLibraryClient)
    {
    }

    public function getBooks(array $searchQuery)
    {
        $response = $this->openLibraryClient->sendRequest(Request::METHOD_GET, self::BOOK_ENDPOINT, $searchQuery);
        $content = $response->getContent();
        $responseData = json_decode($content, true);
        $bookDTOs = [];
        foreach ($responseData['docs'] as $bookData) {
            $openLibraryBook = new OpenLibraryBookDTO();
            $openLibraryBook->authorKeys = $bookData['author_key'] ?? [];
            $openLibraryBook->authorNames = $bookData['author_name'] ?? null;
            $openLibraryBook->coverEditionKey = $bookData['cover_edition_key'] ?? null;
            $openLibraryBook->coverI = $bookData['cover_i'] ?? null;
            $openLibraryBook->editionCount = $bookData['edition_count'] ?? null;
            $openLibraryBook->firstPublishYear = $bookData['first_publish_year'] ?? null;
            $openLibraryBook->hasFulltext = $bookData['has_fulltext'] ?? null;
            $openLibraryBook->ia = $bookData['ia'] ?? [];
            $openLibraryBook->iaCollectionS = $bookData['ia_collection_s'] ?? null;
            $openLibraryBook->key = $bookData['key'] ?? null;
            $openLibraryBook->language = $bookData['language'] ?? null;
            $openLibraryBook->lendingEditionS = $bookData['lending_edition_s'] ?? null;
            $openLibraryBook->lendingIdentifierS = $bookData['lending_identifier_s'] ?? null;
            $openLibraryBook->publicScanB = $bookData['public_scan_b'] ?? null;
            $openLibraryBook->title = $bookData['title'] ?? null;
            $bookDTOs[] = $openLibraryBook;
        }

        return $bookDTOs;
    }
}