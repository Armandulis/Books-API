<?php

namespace App\Factory;

use App\DTO\BookSearchDTO;
use App\DTO\OpenLibraryBookDTO;
use App\Entity\Isbn;
use App\Service\AuthorService;

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
}