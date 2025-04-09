<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class BookRepositoryTest
 * @covers \App\Repository\BookRepository
 */
final class BookRepositoryTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private BookRepository&MockObject $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        // Set up Repository
        $this->bookRepository = $this->getMockBuilder(BookRepository::class)
            ->onlyMethods(['getEntityManager'])
            ->setConstructorArgs([$this->createMock(ManagerRegistry::class)])
            ->getMock();
        $this->bookRepository->method('getEntityManager')->willReturn($this->entityManager);
    }

    public function testSave(): void
    {
        // Given book
        $book = $this->createMock(Book::class);

        // Then we expect to persist and flush
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($book);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // When we call save
        $this->bookRepository->save($book);
    }

}