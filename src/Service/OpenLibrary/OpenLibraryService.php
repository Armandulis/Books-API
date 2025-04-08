<?php

namespace App\Service\OpenLibrary;

use App\DTO\BookSearchDTO;
use App\DTO\OpenLibraryBookDTO;
use App\Factory\BookFactory;

/**
 * Class OpenLibraryService
 */
class OpenLibraryService
{
    /**
     * OpenLibraryService constructor
     * @param OpenLibraryBookService $openLibraryBookService
     * @param BookFactory $bookFactory
     */
    public function __construct(
        private readonly OpenLibraryBookService $openLibraryBookService,
        private readonly BookFactory            $bookFactory,
    )
    {
    }

    /**
     * Send request to find books based on search query
     * @param BookSearchDTO $bookSearchDTO
     * @return array<OpenLibraryBookDTO>
     */
    public function searchBooks(BookSearchDTO $bookSearchDTO): array
    {
        $bookSearchQuery = $this->bookFactory->openLibraryBookSearchQueryFromSearchDTO($bookSearchDTO);
        $booksDTOs = $this->openLibraryBookService->getBooks($bookSearchQuery);
        return $booksDTOs;
    }
}