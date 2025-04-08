<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class BookRepository
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * BookRepository constructor
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Saves the book in the database
     * @param Book $book
     * @return void
     */
    public function save(Book $book): void
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    /**
     * Searches book by author's name
     * @param string $authorName Author's name to search
     * @param int $limit
     * @param int $offset
     * @return array<Book>
     */
    public function matchByAuthorName(string $authorName, int $limit = 100, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('book')
            ->select('book')
            ->innerJoin('book.authors', 'author')
            ->where('LOWER(author.name) LIKE :searchName')
            ->setParameter('searchName', '%' . strtolower($authorName) . '%')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $qb->getResult();
    }

    public function matchByTitle(?string $searchTitle, ?int $limit, int $offset = 0)
    {
        $qb = $this->createQueryBuilder('book')
            ->select('book')
            ->where('LOWER(book.title) LIKE :searchTitle')
            ->setParameter('searchTitle', '%' . strtolower($searchTitle) . '%')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $qb->getResult();
    }
}
