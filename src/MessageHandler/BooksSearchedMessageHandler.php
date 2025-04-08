<?php

namespace App\MessageHandler;

use App\Factory\BookFactory;
use App\Message\BooksSearchedMessage;
use App\Service\BookService;
use App\Service\OpenLibrary\OpenLibraryService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class BooksSearchedMessageHandler
{
    /**
     * BooksSearchedMessageHandler constructor
     * @param OpenLibraryService $openLibraryService
     * @param BookService $bookService
     * @param BookFactory $bookFactory
     */
    public function __construct(
        private readonly OpenLibraryService $openLibraryService,
        private readonly BookService        $bookService,
        private readonly BookFactory        $bookFactory,

    )
    {
    }

    /**
     * Invoked by symfony message listener
     * Searches for books in open library and saves these books in database
     * @param BooksSearchedMessage $message
     * @return void
     */
    public function __invoke(BooksSearchedMessage $message)
    {
        // Search books
        $bookSearchDTO = $message->getBookSearchDTO();
        $bookDTOs = $this->openLibraryService->searchBooks($bookSearchDTO);

        // Save books
        foreach ($bookDTOs as $bookDTO) {
            $book = $this->bookFactory->bookFromOpenLibraryBookDTO($bookDTO);
            $this->bookService->saveBook($book);
        }
    }
}
