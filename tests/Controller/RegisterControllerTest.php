<?php

namespace App\Tests\Controller;

use App\Controller\RegisterController;
use App\DTO\ErrorResponseDTO;
use App\Service\UserService;
use App\Service\UserValidationService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RegisterControllerTest
 * @covers \App\Controller\RegisterController
 */
final class RegisterControllerTest extends TestCase
{
    public Request $request;
    private UserService&MockObject $userService;
    private UserValidationService&MockObject $userValidationService;
    private RegisterController $registerController;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->request = new Request();
        $this->userService = $this->createMock(UserService::class);
        $this->userValidationService = $this->createMock(UserValidationService::class);

        // Set up controller
        $this->registerController = new RegisterController($this->userService, $this->userValidationService);
        $container = $this->createMock(ContainerInterface::class);
        $this->registerController->setContainer($container);
    }

    public function testRegister(): void
    {
        // Given user provided email, password
        $this->request->request->set('password', 'shrekPass');
        $this->request->request->set('email', 'shrekEmail@gmail.com');

        // Given input is valid
        $this->userValidationService->method('validateRegisterUser')->willReturn(null);

        // Then we expect to call createUser with user and plaintextPassword
        $this->userService->expects(self::once())->method('createUser')->with(self::anything(), 'shrekPass');

        // When we call register
        $response = $this->registerController->register($this->request);

        // Then we expect response to contain success message with user email
        self::assertSame('{"data":{"message":"User with email shrekEmail@gmail.com successfully created!"}}', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }

    public function testRegisterInvalidInput(): void
    {
        // Given user provided no email, invalid password
        $this->request->request->set('password', '');

        // Given validation fails
        $this->userValidationService->method('validateRegisterUser')
            ->willReturn(['email' => 'missing', 'password' => 'invalid']);

        // When we call register
        $response = $this->registerController->register($this->request);

        // Then we expect response to contain error message
        self::assertSame(
            json_encode(
                new ErrorResponseDTO(
                    400,
                    'Registration failed',
                    ['email' => 'missing', 'password' => 'invalid']
                )
            ),
            $response->getContent()
        );
        self::assertSame(400, $response->getStatusCode());
    }
}
