<?php

namespace App\Tests\Repository;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthorRepository
 * @covers \App\Repository\AuthorRepository
 */
final class AuthorRepositoryTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private AuthorRepository&MockObject $authorRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        // Set up Repository
        $this->authorRepository = $this->getMockBuilder(AuthorRepository::class)
            ->onlyMethods(['getEntityManager'])
            ->setConstructorArgs([$this->createMock(ManagerRegistry::class)])
            ->getMock();
        $this->authorRepository->method('getEntityManager')->willReturn($this->entityManager);
    }

    public function testSave(): void
    {
        // Given Author
        $author = $this->createMock(Author::class);

        // Then we expect to persist and flush
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($author);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // When we call save
        $this->authorRepository->save($author);
    }
}