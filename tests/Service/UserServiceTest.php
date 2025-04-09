<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserServiceTest
 */
final class UserServiceTest extends TestCase
{
    private UserPasswordHasherInterface&MockObject $userPasswordHasher;
    private UserRepository&MockObject $userRepository;
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        // Set up service
        $this->userService = new UserService($this->userPasswordHasher, $this->userRepository);
    }

    public function testCreateUser(): void
    {
        // Given user with password
        $plaintextPassword = 'testPassword123';
        $user = new User();

        // Given hashPassword returns hashed password
        $hashedPassword = 'hashedPassword123';
        $this->userPasswordHasher->method('hashPassword')->willReturn($hashedPassword);

        // Then we expect to call hashPassword once with user and password
        $this->userPasswordHasher->expects(self::once())->method('hashPassword')->with($user, $plaintextPassword);

        // Then we expect to save user
        $this->userRepository->expects(self::once())->method('save')->with($user);

        // When we call createUser
        $this->userService->createUser($user, $plaintextPassword);

        // Then we expect hashed password to be set
        self::assertEquals($hashedPassword, $user->getPassword());
    }

    public function testExistsByEmail(): void
    {
        // Given email exists in the system
        $email = 'existing@email.com';
        $user = new User();
        $this->userRepository->method('findOneBy')->willReturn($user);

        // When we call existsByEmail
        $result = $this->userService->existsByEmail($email);

        // Then we expect to get true
        self::assertTrue($result);
    }

    public function testExistsByEmailWithNonexistentEmail(): void
    {
        // Given email does not exist
        $email = 'nonexistent@email.com';
        $this->userRepository->method('findOneBy')->willReturn(null);

        // When we call existsByEmail
        $result = $this->userService->existsByEmail($email);

        // Then we expect to get false
        self::assertFalse($result);
    }
}
