<?php

namespace App\Tests\Factory;

use App\DTO\BookSearchDTO;
use App\DTO\OpenLibraryBookDTO;
use App\Factory\BookFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class BookFactoryTest
 * @covers \App\Factory\BookFactory
 */
final class BookFactoryTest extends TestCase
{
    private BookFactory $bookFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookFactory = new BookFactory();
    }

    public function testSearchDTOFromSearchRequestWithAllValues(): void
    {
        // Given search query
        $searchQuery = [
            'searchType' => 'title',
            'searchValue' => 'The Hobbit',
            'page' => 2,
            'limit' => 25
        ];

        // When we call searchDTOFromSearchRequest
        $dto = $this->bookFactory->searchDTOFromSearchRequest($searchQuery);

        // Then we expect correct values to be set
        self::assertSame('title', $dto->searchType);
        self::assertSame('The Hobbit', $dto->searchValue);
        self::assertSame(2, $dto->page);
        self::assertSame(25, $dto->limit);
    }

    public function testSearchDTOFromSearchRequestWithNoValues(): void
    {
        // Given no search query
        // When we call searchDTOFromSearchRequest
        $dto = $this->bookFactory->searchDTOFromSearchRequest([]);

        // Then we expect correct values to be set
        self::assertNull($dto->searchType);
        self::assertNull($dto->searchValue);
        self::assertSame(1, $dto->page);
        self::assertSame(100, $dto->limit);
    }

    public function testOpenLibraryBookSearchQueryFromSearchDTO(): void
    {
        // Given a BookSearchDTO with specific values
        $bookSearchDTO = new BookSearchDTO();
        $bookSearchDTO->searchType = 'title';
        $bookSearchDTO->searchValue = 'The Hobbit';
        $bookSearchDTO->page = 2;
        $bookSearchDTO->limit = 20;

        // When we call openLibraryBookSearchQueryFromSearchDTO
        $query = $this->bookFactory->openLibraryBookSearchQueryFromSearchDTO($bookSearchDTO);

        // Then the returned array should contain the expected query parameters
        self::assertSame(20, $query['query']['limit']);
        self::assertSame(20, $query['query']['offset']);
        self::assertSame('The Hobbit', $query['query']['title']);
    }

    public function testIsbnFromOpenLibraryBookDTO(): void
    {
        // Given an OpenLibraryBookDTO with 'ia' containing ISBN entries
        $openLibraryBookDTO = new OpenLibraryBookDTO();
        $openLibraryBookDTO->ia = [
            'isbn_1234567890',
            'isbn_0987654321',
            'other_field_something',
            'isbn_1122334455',
        ];

        // When calling isbnFromOpenLibraryBookDTO
        $isbns = $this->bookFactory->isbnFromOpenLibraryBookDTO($openLibraryBookDTO);

        // Then the returned array should contain Isbn with the correct ISBN values
        self::assertCount(3, $isbns);
        self::assertSame('isbn_1234567890', $isbns[0]->getIsbn());
        self::assertSame('isbn_0987654321', $isbns[1]->getIsbn());
        self::assertSame('isbn_1122334455', $isbns[2]->getIsbn());
    }

    public function testIsbnFromOpenLibraryBookDTOWithEmptyIa(): void
    {
        // Given an OpenLibraryBookDTO with an empty 'ia' field
        $openLibraryBookDTO = new OpenLibraryBookDTO();
        $openLibraryBookDTO->ia = [];

        // When calling isbnFromOpenLibraryBookDTO
        $isbns = $this->bookFactory->isbnFromOpenLibraryBookDTO($openLibraryBookDTO);

        // Then the returned array should be empty
        self::assertCount(0, $isbns);
    }

    public function testOpenLibraryBookDTOFromResponse(): void
    {
        // Given book data from open api response
        $bookData = [
            'author_key' => ['author1', 'author2'],
            'author_name' => ['John Doe'],
            'cover_edition_key' => '12345',
            'cover_i' => '67890',
            'edition_count' => 5,
            'first_publish_year' => 2000,
            'has_fulltext' => true,
            'ia' => ['isbn_1234567890', 'isbn_9876543210'],
            'ia_collection_s' => 'archive.org',
            'key' => 'OL1234567M',
            'language' => ['eng'],
            'lending_edition_s' => '1st Edition',
            'lending_identifier_s' => '9876543210',
            'public_scan_b' => true,
            'title' => 'The Great Book'
        ];

        // When we call openLibraryBookDTOFromResponse
        $openLibraryBookDTO = $this->bookFactory->openLibraryBookDTOFromResponse($bookData);

        // Then we expect correct dto values
        self::assertInstanceOf(OpenLibraryBookDTO::class, $openLibraryBookDTO);
        self::assertSame(['author1', 'author2'], $openLibraryBookDTO->authorKeys);
        self::assertSame(['John Doe'], $openLibraryBookDTO->authorNames);
        self::assertSame('12345', $openLibraryBookDTO->coverEditionKey);
        self::assertSame(67890, $openLibraryBookDTO->coverI);
        self::assertSame(5, $openLibraryBookDTO->editionCount);
        self::assertSame(2000, $openLibraryBookDTO->firstPublishYear);
        self::assertTrue($openLibraryBookDTO->hasFulltext);
        self::assertSame(['isbn_1234567890', 'isbn_9876543210'], $openLibraryBookDTO->ia);
        self::assertSame('archive.org', $openLibraryBookDTO->iaCollectionS);
        self::assertSame('OL1234567M', $openLibraryBookDTO->key);
        self::assertSame(['eng'], $openLibraryBookDTO->language);
        self::assertSame('1st Edition', $openLibraryBookDTO->lendingEditionS);
        self::assertSame('9876543210', $openLibraryBookDTO->lendingIdentifierS);
        self::assertTrue($openLibraryBookDTO->publicScanB);
        self::assertSame('The Great Book', $openLibraryBookDTO->title);
    }
}
