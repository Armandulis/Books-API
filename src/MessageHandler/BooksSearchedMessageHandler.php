<?php

namespace App\MessageHandler;

use App\Entity\Author;
use App\Entity\Book;
use App\Factory\BookFactory;
use App\Message\BooksSearchedMessage;
use App\Service\AuthorService;
use App\Service\BookService;
use App\Service\IsbnService;
use App\Service\OpenLibrary\OpenLibraryService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Throwable;

#[AsMessageHandler]
class BooksSearchedMessageHandler
{
    public const MESSAGE_DELAY = 5 * 60 * 1000;
    public const MAX_ATTEMPTS = 5;

    /**
     * BooksSearchedMessageHandler constructor
     * @param OpenLibraryService $openLibraryService
     * @param BookService $bookService
     * @param BookFactory $bookFactory
     * @param AuthorService $authorService
     * @param IsbnService $isbnService
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        private readonly OpenLibraryService  $openLibraryService,
        private readonly BookService         $bookService,
        private readonly BookFactory         $bookFactory,
        private readonly AuthorService       $authorService,
        private readonly IsbnService         $isbnService,
        private readonly MessageBusInterface $messageBus,
    )
    {
    }

    /**
     * Invoked by symfony message listener
     * Searches for books in open library and saves these books in database
     * @param BooksSearchedMessage $message
     * @return void
     * @throws ExceptionInterface
     */
    public function __invoke(BooksSearchedMessage $message)
    {
        try {
            // Search books
            $bookSearchDTO = $message->getBookSearchDTO();

            $bookDTOs = $this->openLibraryService->searchBooks($bookSearchDTO);

            foreach ($bookDTOs as $bookDTO) {

                $authors = [];
                foreach ($bookDTO->authorKeys as $key => $externalId) {
                    $author = $this->authorService->findOneBy(['externalId' => $externalId]) ?? new Author();
                    $author->setExternalId($externalId);
                    $author->setName($bookDTO->authorNames[$key]);
                    $this->authorService->save($author);
                    $authors[] = $author;
                }

                $book = $this->bookService->findOneBy(['externalId' => $bookDTO->key]) ?? new Book();
                $book->setTitle($bookDTO->title);
                $book->setExternalId($bookDTO->key);
                $book->setFirstPublishYear($bookDTO->firstPublishYear);
                $book->replaceAuthors($authors);
                $book->getIsbns()->clear();
                $this->bookService->save($book);

                $isbns = $this->bookFactory->isbnFromOpenLibraryBookDTO($bookDTO);
                foreach ($isbns as $isbn) {
                    $isbn->setBook($book);
                    $this->isbnService->save($isbn);
                }
            }
        } catch (Throwable $throwable) {
            // Rate limit is returned as 403
            if ($throwable->getCode() === 403) {
                if( $message->getAttemptNumber() == self::MAX_ATTEMPTS)
                {
                    // Log that we failed up to maximum attempts and queue message to dead letter queue.
                    return;
                }

                // Queue book searched message
                $this->messageBus->dispatch(
                    new BooksSearchedMessage($bookSearchDTO, $message->getAttemptNumber() + 1),
                    [new DelayStamp(self::MESSAGE_DELAY)]
                );
                return;
            }
            // Log exception and queue message to dead letter queue.
        }

    }
}