<?php

namespace App\Controller\V2;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v2/books', name: 'api_v2_books_')]
class BookController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function searchBooks(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/V2/BookController.php',
        ]);
    }
}
