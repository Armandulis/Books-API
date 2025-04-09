<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;

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
     * Finds one book by criteria, null if book was not found
     * @param array<string, mixed> $criteria
     * @return Book|null
     */
    public function findOneBy(array $criteria): ?Book
    {
        return $this->bookRepository->findOneBy($criteria);
    }

    /**
     * Save book, if book already exists, update existing book
     * @param Book $book
     * @return void
     */
    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }


    /**
     * Finds books that matches title
     * @param string $searchValue
     * @param int|null $limit
     * @param int|null $page
     * @return array<Book>
     */
    public function matchByTitle(string $searchValue, ?int $limit = null, ?int $page = null): array
    {
        return $this->bookRepository->matchByTitle($searchValue, $limit, ($page - 1) * $limit);
    }

    /**
     * Finds books that matches author's name
     * @param string $searchValue
     * @param int|null $limit
     * @param int|null $page
     * @return array<Book>
     */
    public function matchByAuthorName(string $searchValue, ?int $limit = null, ?int $page = null): array
    {
        return $this->bookRepository->matchByAuthorName($searchValue, $limit, ($page - 1) * $limit);
    }
}
