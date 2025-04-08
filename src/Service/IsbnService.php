<?php

namespace App\Service;

use App\Entity\Isbn;
use App\Repository\IsbnRepository;

/**
 * Class IsbnService
 */
class IsbnService
{
    /**
     * IsbnService constructor
     * @param IsbnRepository $isbnRepository
     */
    public function __construct(private readonly IsbnRepository $isbnRepository)
    {
    }

    /**
     * Finds one isbn by criteria
     * @param array $criteria
     * @return Isbn|null
     */
    public function findOneBy(array $criteria): ?Isbn
    {
        return $this->isbnRepository->findOneBy($criteria);
    }
}