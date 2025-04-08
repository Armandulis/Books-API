<?php

namespace App\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;

/**
 * Class AuthorService
 */
class AuthorService
{
    /**
     * AuthorService constructor
     * @param AuthorRepository $authorRepository
     */
    public function __construct(
        private readonly AuthorRepository $authorRepository,
    )
    {
    }


    /**
     * Find one author, null if author was not found
     * @param array<string, mixed> $criteria
     * @return Author|null
     */
    public function findOneBy(array $criteria): ?Author
    {
        return $this->authorRepository->findOneBy($criteria);
    }


    /**
     * Saves author to the database
     * @param Author $author
     * @return void
     */
    public function save(Author $author): void
    {
        $this->authorRepository->save($author);
    }
}