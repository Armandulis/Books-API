<?php

namespace App\Controller\V2;

use App\DTO\ErrorResponseDTO;
use App\Enum\BookSearchTypeEnum;
use App\Factory\BookFactory;
use App\Service\BookSearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/v2/books', name: 'api_v2_books_')]
class BookController extends AbstractController
{
    /**
     * BookController constructor
     * @param BookSearchService $bookSearchService
     * @param BookFactory $bookFactory
     */
    public function __construct(
        private readonly BookSearchService $bookSearchService,
        private readonly BookFactory       $bookFactory,
    )
    {
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function searchBooks(Request $request): JsonResponse
    {
        // Get user's input
        $searchQuery = $request->query->all();
        $bookSearchDTO = $this->bookFactory->searchDTOFromSearchRequest($searchQuery);

        // Validate user's input
        if (empty($bookSearchDTO->searchType) || BookSearchTypeEnum::tryFrom($bookSearchDTO->searchType) === null) {
            return $this->json(new ErrorResponseDTO(400, 'Missing search type'));
        }

        if (empty($bookSearchDTO->searchValue)) {
            return $this->json(new ErrorResponseDTO(400, 'Missing search value'));
        }

        // Search for books and return books
        try {
            $books = $this->bookSearchService->search($bookSearchDTO);
            return $this->json(['data' => $books]);
        } catch (Throwable $throwable) {
            dd($throwable);
            // 1. Log $throwable in places like Sentry
            // 2. Return error to the user - avoid sending actual error as it may include sensitive data
            return $this->json(new ErrorResponseDTO(500, 'Could not process the request'));
        }
    }
}
