<?php

namespace App\Tests\DTO;

use App\DTO\OpenLibraryBookDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class OpenLibraryBookDTOTest
 * @covers \App\DTO\OpenLibraryBookDTO
 */
final class OpenLibraryBookDTOTest extends TestCase
{
    public function testOpenLibraryBookDTO(): void
    {
        // Given OpenLibraryBookDTO with correct values
        $dto = new OpenLibraryBookDTO();
        $dto->authorKeys = ['A1', 'A2'];
        $dto->authorNames = ['Author One', 'Author Two'];
        $dto->coverEditionKey = 'OL12345M';
        $dto->coverI = 123456;
        $dto->editionCount = 5;
        $dto->firstPublishYear = 2001;
        $dto->hasFulltext = true;
        $dto->ia = ['ia1', 'ia2'];
        $dto->iaCollectionS = 'collection1';
        $dto->key = '/books/OL12345M';
        $dto->language = ['eng', 'fre'];
        $dto->lendingEditionS = 'OL67890M';
        $dto->lendingIdentifierS = 'lender123';
        $dto->publicScanB = false;
        $dto->title = 'Throne of Glass';

        // Then we expect correct values to be set
        self::assertSame(['A1', 'A2'], $dto->authorKeys);
        self::assertSame(['Author One', 'Author Two'], $dto->authorNames);
        self::assertSame('OL12345M', $dto->coverEditionKey);
        self::assertSame(123456, $dto->coverI);
        self::assertSame(5, $dto->editionCount);
        self::assertSame(2001, $dto->firstPublishYear);
        self::assertTrue($dto->hasFulltext);
        self::assertSame(['ia1', 'ia2'], $dto->ia);
        self::assertSame('collection1', $dto->iaCollectionS);
        self::assertSame('/books/OL12345M', $dto->key);
        self::assertSame(['eng', 'fre'], $dto->language);
        self::assertSame('OL67890M', $dto->lendingEditionS);
        self::assertSame('lender123', $dto->lendingIdentifierS);
        self::assertFalse($dto->publicScanB);
        self::assertSame('Throne of Glass', $dto->title);
    }
}