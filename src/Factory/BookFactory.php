<?php

namespace App\Factory;

use App\DTO\BookSearchDTO;
use App\DTO\OpenLibraryBookDTO;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Isbn;
use App\Service\AuthorService;

/**
 * Class BookFactory
 */
class BookFactory
{
    /**
     * BookFactory constructor
     * @param AuthorService $authorService
     */
    public function __construct(
        private readonly AuthorService $authorService
    )
    {
    }

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
     * Creates book from OpenLibraryBookDTO
     * @param OpenLibraryBookDTO $openLibraryBookDTO
     * @return Book
     */
    public function bookFromOpenLibraryBookDTO(OpenLibraryBookDTO $openLibraryBookDTO): Book
    {
        $book = new Book();
        $book->setTitle($openLibraryBookDTO->title);
        $book->setExternalId($openLibraryBookDTO->key);
        $isbnList = array_filter($openLibraryBookDTO->ia, fn($entry) => str_starts_with($entry, 'isbn_'));
        foreach ($isbnList as $isbnValue) {
            $isbn = new Isbn();
            $isbn->setIsbn($isbnValue);
            $book->addIsbn($isbn);
        }

        foreach ($openLibraryBookDTO->authorKeys as $key => $externalId) {
            $author = $this->authorService->findOneBy($externalId) ?? new Author();
            $author->setExternalId($externalId);
            $author->setName($openLibraryBookDTO->authorNames[$key]);
            $book->addAuthor($author);
        }

        $book->setFirstPublishYear($openLibraryBookDTO->firstPublishYear);
        return $book;
    }
}