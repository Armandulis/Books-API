<?php

namespace App\Service;


use App\DTO\BookSearchDTO;
use App\Entity\Book;
use App\Enum\BookSearchTypeEnum;
use App\Message\BooksSearchedMessage;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class BookSearchService
 */
class BookSearchService
{
    /**
     * BookSearchService constructor
     * @param BookService $bookService
     * @param MessageBusInterface $messageBus
     * @param IsbnService $isbnService
     */
    public function __construct(
        private readonly BookService         $bookService,
        private readonly MessageBusInterface $messageBus,
        private readonly IsbnService         $isbnService,
    )
    {
    }


    /**
     * Fetches books from database and queues a BookSearchedMessage
     * @param BookSearchDTO $bookSearchDTO
     * @return array<Book>
     * @throws ExceptionInterface
     */
    public function search(BookSearchDTO $bookSearchDTO): array
    {
        // Queue book searched message
        $this->messageBus->dispatch(new BooksSearchedMessage($bookSearchDTO));

        // Search the database
        if ($bookSearchDTO->searchType === BookSearchTypeEnum::TITLE->value) {
            return $this->bookService->findBy(
                ['title' => $bookSearchDTO->searchValue],
                $bookSearchDTO->limit,
                $bookSearchDTO->page
            );
        }

        if ($bookSearchDTO->searchType === BookSearchTypeEnum::ISBN->value) {
            $isbn = $this->isbnService->findOneBy(['isbn' => $bookSearchDTO->searchValue]);
            return $isbn?->getBook() ? [$isbn->getBook()] : [];
        }

        if ($bookSearchDTO->searchType === BookSearchTypeEnum::AUTHOR->value) {
            return $this->bookService->matchByAuthorName($bookSearchDTO->searchValue,
                $bookSearchDTO->limit,
                $bookSearchDTO->page
            );
        }

        return [];
    }
}
