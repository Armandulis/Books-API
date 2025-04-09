<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class UserRepositoryTest
 * @covers \App\Repository\UserRepository
 */
final class UserRepositoryTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private UserRepository&MockObject $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        // Set up Repository
        $this->userRepository = $this->getMockBuilder(UserRepository::class)
            ->onlyMethods(['getEntityManager'])
            ->setConstructorArgs([$this->createMock(ManagerRegistry::class)])
            ->getMock();
        $this->userRepository->method('getEntityManager')->willReturn($this->entityManager);
    }

    public function testSave(): void
    {
        // Given User
        $user = $this->createMock(User::class);

        // Then we expect to persist and flush
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($user);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // When we call save
        $this->userRepository->save($user);
    }
}
