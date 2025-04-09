<?php

namespace App\Tests\Service;

use App\Service\UserService;
use App\Service\UserValidationService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class UserValidationServiceTest
 * @covers \App\Service\UserValidationService
 */
final class UserValidationServiceTest extends TestCase
{
    private UserService&MockObject $userService;
    private UserValidationService $userValidationService;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->userService = $this->createMock(UserService::class);

        // Set up service
        $this->userValidationService = new UserValidationService($this->userService);
    }

    public function testValidateEmailEmpty(): void
    {
        // Given email is empty
        $email = '';

        // When we call validateEmail
        $result = $this->userValidationService->validateEmail($email);

        // Then we expect to receive error message
        self::assertSame('Invalid email address', $result);
    }

    public function testValidateEmailInvalid(): void
    {
        // Given email is invalid
        $email = 'email@';

        // When we call validateEmail
        $result = $this->userValidationService->validateEmail($email);

        // Then we expect to receive error message
        self::assertSame('Invalid email address', $result);
    }

    public function testValidateEmailCorrect(): void
    {
        // Given email is valid
        $email = 'email@gmail.com';

        // When we call validateEmail
        $result = $this->userValidationService->validateEmail($email);

        // Then we expect to receive null
        self::assertNull($result);
    }

    public function testValidateEmailExists(): void
    {
        // Given email exists
        $email = 'exists@gmail.com';
        $this->userService->method('existsByEmail')->willReturn(true);

        // Then we expect to call exists by email once
        $this->userService->expects(self::once())->method('existsByEmail')->with($email);

        // When we call validateEmail
        $result = $this->userValidationService->validateEmail($email);

        // Then we expect an error message
        self::assertSame('Email already exists', $result);
    }

    public function testValidatePasswordInvalid(): void
    {
        // Given password is invalid
        $password = '';

        // When we call validatePassword
        $result = $this->userValidationService->validatePassword($password);

        // Then we expect to receive error message
        self::assertSame('Invalid password', $result);
    }

    public function testValidatePasswordCorrect(): void
    {
        // Given password is valid
        $password = 'real-password';

        // When we call validatePassword
        $result = $this->userValidationService->validatePassword($password);

        // Then we expect to receive null
        self::assertNull($result);
    }

    public function testValidateRegisterUserCorrect(): void
    {
        // Given password and email is valid
        $password = 'real-password';
        $email = 'email@gmail.com';

        // When we call validateRegisterUser
        $result = $this->userValidationService->validateRegisterUser($email, $password);

        // Then we expect to receive null
        self::assertNull($result);
    }

    public function testValidateRegisterUserAllInvalid(): void
    {
        // Given email and password is invalid
        $email = '';
        $password = '';

        // When we call validateRegisterUser
        $result = $this->userValidationService->validateRegisterUser($email, $password);

        // Then we expect to receive a list of error messages
        self::assertSame(['email' => 'Invalid email address', 'password' => 'Invalid password'], $result);
    }
}