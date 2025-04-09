<?php

namespace App\Controller;

use App\DTO\ErrorResponseDTO;
use App\Entity\User;
use App\Service\UserService;
use App\Service\UserValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class RegisterController extends AbstractController
{
    /**
     * RegisterController constructor
     * @param UserService $userService
     * @param UserValidationService $userValidationService
     */
    public function __construct(
        private readonly UserService           $userService,
        private readonly UserValidationService $userValidationService
    )
    {
    }

    /**
     * Handles the registration process.
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        // Get user's input
        $plaintextPassword = $request->request->get('password');
        $email = $request->request->get('email');

        // Validate input
        $errors = $this->userValidationService->validateRegisterUser($email, $plaintextPassword);
        if (!empty($errors)) {
            return $this->json(
                new ErrorResponseDTO(
                    Response::HTTP_BAD_REQUEST,
                    'Registration failed',
                    $errors
                ),
                Response::HTTP_BAD_REQUEST
            );
        }

        // Create user
        $user = new User();
        $user->setEmail($email);
        $this->userService->createUser($user, $plaintextPassword);

        return $this->json([
            'data' => [
                'message' => 'User with email ' . $user->getEmail() . ' successfully created!',
            ]
        ]);
    }
}
