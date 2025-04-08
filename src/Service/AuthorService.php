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
     * Finds one author by externalId
     * @param mixed $externalId
     * @return Author|null
     */
    public function findOneBy(string $externalId): ?Author
    {
        return $this->authorRepository->findOneBy(['externalId' => $externalId]);
    }
}