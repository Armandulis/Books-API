<?php

namespace App\Repository;

use App\Entity\Isbn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class IsbnRepository
 * @extends ServiceEntityRepository<Isbn>
 */
class IsbnRepository extends ServiceEntityRepository
{
    /**
     * IsbnRepository constructor
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Isbn::class);
    }
}
