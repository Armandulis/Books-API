<?php

namespace App\Factory;

use App\DTO\BookSearchDTO;
use App\DTO\OpenLibraryBookDTO;
use App\Entity\Isbn;

/**
 * Class BookFactory
 */
class BookFactory
{
    /**
     * Creates a BookSearchDTO from array
     * @param array $searchQuery
     * @return BookSearchDTO
     */
    public function searchDTOFromSearchRequest(array $searchQuery): BookSearchDTO
    {
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = $searchQuery['searchType'] ?? null;
        $bookSearchDTO->searchValue = $searchQuery['searchValue'] ?? null;
        $bookSearchDTO->page = $searchQuery['page'] ?? $bookSearchDTO->page;
        $bookSearchDTO->limit = $searchQuery['limit'] ?? $bookSearchDTO->limit;
        return $bookSearchDTO;
    }

    /**
     * Returns query parameters needed for open library book search
     * @param BookSearchDTO $bookSearchDTO
     * @return array{ query: array{ offset: int, limit: int }}
     */
    public function openLibraryBookSearchQueryFromSearchDTO(BookSearchDTO $bookSearchDTO): array
    {
        return [
            'query' =>
                [
                    'offset' => ($bookSearchDTO->page - 1) * $bookSearchDTO->limit,
                    'limit' => $bookSearchDTO->limit,
                    $bookSearchDTO->searchType => $bookSearchDTO->searchValue
                ],
        ];
    }

    /**
     * Gets isbn values from array of strings, and creates Isbn entities from them
     * @param OpenLibraryBookDTO $openLibraryBookDTO
     * @return array<Isbn>
     */
    public function isbnFromOpenLibraryBookDTO(OpenLibraryBookDTO $openLibraryBookDTO): array
    {
        $isbns = [];
        $isbnList = array_filter($openLibraryBookDTO->ia, fn($entry) => str_starts_with($entry, 'isbn_'));
        foreach ($isbnList as $isbnValue) {
            $isbn = new Isbn();
            $isbn->setIsbn($isbnValue);
            $isbns[] = $isbn;
        }
        return $isbns;
    }

    /**
     * Converts response book data
     * @param array<string, mixed> $bookData
     * @return OpenLibraryBookDTO
     */
    public function openLibraryBookDTOFromResponse(array $bookData): OpenLibraryBookDTO
    {
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
        return $openLibraryBook;
    }
}
