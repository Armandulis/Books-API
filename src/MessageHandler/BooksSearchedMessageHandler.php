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

#[AsMessageHandler]
class BooksSearchedMessageHandler
{
    /**
     * BooksSearchedMessageHandler constructor
     * @param OpenLibraryService $openLibraryService
     * @param BookService $bookService
     * @param BookFactory $bookFactory
     * @param AuthorService $authorService
     * @param IsbnService $isbnService
     */
    public function __construct(
        private readonly OpenLibraryService $openLibraryService,
        private readonly BookService        $bookService,
        private readonly BookFactory        $bookFactory,
        private readonly AuthorService      $authorService,
        private readonly IsbnService        $isbnService,

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
    }
}