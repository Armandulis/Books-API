<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 * @covers \App\Entity\User
 */
final class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up entity
        $this->user = new User();
    }

    public function testEmail(): void
    {
        // Given email
        $this->user->setEmail('test@example.com');

        // When we call getUser
        $result = $this->user->getEmail();

        // Then we expect to get the same email back
        self::assertEquals('test@example.com', $result);
    }

    public function testUserIdentifier(): void
    {
        // Given an email
        $this->user->setEmail('test@example.com');

        // When we call getUserIdentifier
        $result = $this->user->getUserIdentifier();

        // Then we expect to get the same email back
        self::assertEquals('test@example.com', $result);
    }

    public function testRoles(): void
    {
        // Given a role
        $this->user->setRoles(['ROLE_TEST']);

        // When we call getRoles
        $result = $this->user->getRoles();

        // Then we expect to get the set role, along with the default 'ROLE_USER'
        self::assertContains('ROLE_TEST', $result);
        self::assertContains('ROLE_USER', $result);
    }

    public function testPassword(): void
    {
        // Given a password
        $this->user->setPassword('password123');

        // When we call getPassword
        $result = $this->user->getPassword();

        // Then we expect to get the same password back
        self::assertEquals('password123', $result);
    }

    public function testEraseCredentials(): void
    {
        // Given a password is set
        $this->user->setPassword('password123');

        // When we erase credentials
        $this->user->eraseCredentials();

        // Then we expect the password to remain unchanged (no data is erased in the current implementation)
        $result = $this->user->getPassword();
        self::assertEquals('password123', $result);
    }
}
