<?php

namespace App\Tests\MessageHandler;

use App\DTO\BookSearchDTO;
use App\DTO\OpenLibraryBookDTO;
use App\Entity\Isbn;
use App\Factory\BookFactory;
use App\Message\BooksSearchedMessage;
use App\MessageHandler\BooksSearchedMessageHandler;
use App\Service\AuthorService;
use App\Service\BookService;
use App\Service\IsbnService;
use App\Service\OpenLibrary\OpenLibraryService;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class BooksSearchedMessageHandlerTest
 * @covers \App\MessageHandler\BooksSearchedMessageHandler
 */
final class BooksSearchedMessageHandlerTest extends TestCase
{
    private OpenLibraryService&MockObject $openLibraryService;
    private BookService&MockObject $bookService;
    private BookFactory&MockObject $bookFactory;
    private AuthorService&MockObject $authorService;
    private IsbnService&MockObject $isbnService;
    private MessageBusInterface&MockObject $messageBus;

    private BooksSearchedMessageHandler $booksSearchedMessageHandler;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->openLibraryService = $this->createMock(OpenLibraryService::class);
        $this->bookService = $this->createMock(BookService::class);
        $this->bookFactory = $this->createMock(BookFactory::class);
        $this->authorService = $this->createMock(AuthorService::class);
        $this->isbnService = $this->createMock(IsbnService::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);

        // Set up handler
        $this->booksSearchedMessageHandler = new BooksSearchedMessageHandler(
            $this->openLibraryService,
            $this->bookService,
            $this->bookFactory,
            $this->authorService,
            $this->isbnService,
            $this->messageBus
        );
    }

    public function testSearchAndSaveBooks(): void
    {
        // Given book searched message with book search dto
        $bookSearchDto = new BookSearchDTO();
        $bookSearchedMessage = new BooksSearchedMessage($bookSearchDto);

        // Given we receive books from search
        $openLibraryBookDTO = new OpenLibraryBookDTO();
        $openLibraryBookDTO->authorKeys = ['A1'];
        $openLibraryBookDTO->authorNames = ['Author One'];
        $openLibraryBookDTO->coverEditionKey = 'OL12345M';
        $openLibraryBookDTO->coverI = 123456;
        $openLibraryBookDTO->editionCount = 5;
        $openLibraryBookDTO->firstPublishYear = 2001;
        $openLibraryBookDTO->hasFulltext = true;
        $openLibraryBookDTO->ia = ['ia1', 'ia2'];
        $openLibraryBookDTO->iaCollectionS = 'collection1';
        $openLibraryBookDTO->key = '/books/OL12345M';
        $openLibraryBookDTO->language = ['eng', 'fre'];
        $openLibraryBookDTO->lendingEditionS = 'OL67890M';
        $openLibraryBookDTO->lendingIdentifierS = 'lender123';
        $openLibraryBookDTO->publicScanB = false;
        $openLibraryBookDTO->title = 'Throne of Glass';

        // Then we expect to search for books
        $this->openLibraryService->expects(self::once())
            ->method('searchBooks')
            ->willReturn([$openLibraryBookDTO]);

        // Then we expect to search for author by external id
        // Given there is no author with this external id
        $this->authorService->expects(self::once())
            ->method('findOneBy')
            ->with(['externalId' => 'A1']);

        // Then we expect to save new author
        $this->authorService->expects(self::once())
            ->method('save');

        // Then we expect to save new book
        $this->bookService->expects(self::once())
            ->method('save');

        // Given isbn from dto
        $isbn = new Isbn();
        $this->bookFactory->expects(self::once())
            ->method('isbnFromOpenLibraryBookDTO')
            ->willReturn([$isbn]);

        // Then we expect to save new isbn
        $this->isbnService->expects(self::once())
            ->method('save');

        // Then we expect to never dispatch new message
        $this->messageBus->expects(self::never())
            ->method('dispatch');

        // When we call invoke
        $this->booksSearchedMessageHandler->__invoke($bookSearchedMessage);
    }


    public function testSearchAndSaveBooksThrownException(): void
    {
        // Given search fails because of rate limit (403)
        $bookSearchedMessage = new BooksSearchedMessage($this->createMock(BookSearchDTO::class));
        $this->openLibraryService->method('searchBooks')
            ->willThrowException(new Exception('Rate limit reached', 403));

        // Then we expect to dispatch new message with counter + 1
        $this->messageBus->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(function (BooksSearchedMessage $msg) {
                    return $msg->getAttemptNumber() === 2;
                }
                )
            )
            ->willReturn(Envelope::wrap($bookSearchedMessage));

        // When we call invoke
        $this->booksSearchedMessageHandler->__invoke($bookSearchedMessage);
    }

    public function testSearchAndSaveBooksThrownExceptionRetriesLimitReached(): void
    {
        // Given search fails because of rate limit (403) and this is 5th attempt
        $bookSearchedMessage = new BooksSearchedMessage($this->createMock(BookSearchDTO::class), 5);
        $this->openLibraryService->method('searchBooks')
            ->willThrowException(new Exception('Rate limit reached', 403));

        // Then we expect to dispatch never dispatch a new message

        // Then we expect to never dispatch new message
        $this->messageBus->expects(self::never())
            ->method('dispatch');

        // When we call invoke
        $this->booksSearchedMessageHandler->__invoke($bookSearchedMessage);
    }
}
