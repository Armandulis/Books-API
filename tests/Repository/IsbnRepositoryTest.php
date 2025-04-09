<?php

namespace App\Tests\Repository;

use App\Entity\Isbn;
use App\Repository\IsbnRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class IsbnRepositoryTest
 * @covers \App\Repository\IsbnRepository
 */
final class IsbnRepositoryTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private IsbnRepository&MockObject $isbnRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        // Set up Repository
        $this->isbnRepository = $this->getMockBuilder(IsbnRepository::class)
            ->onlyMethods(['getEntityManager'])
            ->setConstructorArgs([$this->createMock(ManagerRegistry::class)])
            ->getMock();
        $this->isbnRepository->method('getEntityManager')->willReturn($this->entityManager);
    }

    public function testSave(): void
    {
        // Given Isbn
        $isbn = $this->createMock(Isbn::class);

        // Then we expect to persist and flush
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($isbn);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // When we call save
        $this->isbnRepository->save($isbn);
    }
}
