<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Exception;

/**
 * Class BookService
 */
class BookService
{
    /**
     * BookService constructor
     * @param BookRepository $bookRepository
     */
    public function __construct(private readonly BookRepository $bookRepository)
    {
    }

    /**
     * Finds Books by criteria
     * @param array<string, int|string|null> $criteria search by specific fields
     * @param int $limit limit results
     * @param int $page
     * @return array<Book>
     */
    public function findBy(array $criteria, int $limit = 100, int $page = 1): array
    {
        return $this->bookRepository->findBy(
            $criteria,
            limit: $limit,
            offset: ($page - 1) * $limit
        );
    }

    /**
     * Save book, if book already exists, update existing book
     * @param Book $book
     * @return void
     */
    public function saveBook(Book $book): void
    {
        // Find existing book by external id
        $existingBook = $this->bookRepository->findOneBy(['externalId' => $book->getExternalId()]);

        // If book doesn't exist, save the new book
        if ($existingBook === null) {
            $this->bookRepository->save($book);
            return;
        }

        assert($existingBook instanceof Book);

        // If book exists, just update values
        $existingBook->setTitle($book->getTitle());
        $existingBook->setFirstPublishYear($book->getFirstPublishYear());
//        $existingBook->syncIsbns($book->getIsbns()->toArray());
//        $existingBook->syncAuthors($book->getAuthors()->toArray());
        $this->bookRepository->save($existingBook);
    }


    /**
     * Finds books that matches title
     * @param string|null $searchValue
     * @param int|null $limit
     * @param int|null $page
     * @return array<Book>
     */
    public function matchByTitle(?string $searchValue, ?int $limit = null, ?int $page = null): array
    {
        return $this->bookRepository->matchByTitle($searchValue, $limit, ($page - 1) * $limit);
    }

    /**
     * Finds books that matches author's name
     * @param string|null $searchValue
     * @param int|null $limit
     * @param int|null $page
     * @return array<Book>
     */
    public function matchByAuthorName(?string $searchValue, ?int $limit = null, ?int $page = null): array
    {
        return $this->bookRepository->matchByAuthorName($searchValue, $limit, ($page - 1) * $limit);
    }
}
