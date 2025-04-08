<?php

namespace App\Controller;

use App\DTO\BookSearchDTO;
use App\DTO\ErrorResponseDTO;
use App\Enum\BookSearchTypeEnum;
use App\Service\BookSearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

/** @deprecated Use V2/BookController */
#[Route('/api/v1/knygos', name: 'api_v1_knygos_')]
class BookController extends AbstractController
{
    public function __construct(
        private readonly BookSearchService $bookSearchService
    )
    {
    }

    #[Route('/ieskoti', name: 'ieskoti', methods: ['GET'])]
    public function searchBooks(Request $request): JsonResponse
    {
        // Get user's input
        $searchQuery = $request->query->all();
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = $searchQuery['paieskosTipas'] ?? null;
        $bookSearchDTO->searchValue = $searchQuery['paieska'] ?? null;
        $bookSearchDTO->page = $searchQuery['puslapis'] ?? $bookSearchDTO->page;
        $bookSearchDTO->limit = $searchQuery['limitas'] ?? $bookSearchDTO->limit;

        // Validate user's input
        if (empty($bookSearchDTO->searchType) || BookSearchTypeEnum::tryFrom($bookSearchDTO->searchType) === null) {
            return $this->json(new ErrorResponseDTO(400, 'Nera paieskos tipo'));
        }

        if (empty($bookSearchDTO->searchType)) {
            return $this->json(new ErrorResponseDTO(400, 'Nera paieskos'));
        }

        // Search for books and return books
        try {
            $books = $this->bookSearchService->search($bookSearchDTO);
            return $this->json(['data' => $books]);
        } catch (Throwable $throwable) {
            // 1. Log exception in places like Sentry
            // 2. Return error to the user - avoid sending actual error as it may include sensitive data
            return $this->json(new ErrorResponseDTO(500, 'Could not process the request'));
        }
    }
}
