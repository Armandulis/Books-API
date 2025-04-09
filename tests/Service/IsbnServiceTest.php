<?php

namespace App\Tests\Service;

use App\Entity\Isbn;
use App\Repository\IsbnRepository;
use App\Service\IsbnService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class IsbnServiceTest
 * @covers \App\Service\IsbnService
 */
final class IsbnServiceTest extends TestCase
{
    private IsbnRepository&MockObject $isbnRepository;

    private IsbnService $isbnService;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->isbnRepository = $this->createMock(IsbnRepository::class);

        // Set up service
        $this->isbnService = new IsbnService($this->isbnRepository);
    }

    public function testFindOneBy(): void
    {
        // Given ISBN and criteria
        $isbn = new Isbn();
        $isbn->setIsbn('978-3-16-148410-0');
        $criteria = ['isbn' => '978-3-16-148410-0'];

        // Then we expect the repository's findOneBy method to be called with the criteria
        // Given isbn exists in the database
        $this->isbnRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with($criteria)
            ->willReturn($isbn);

        // When we call findOneBy
        $result = $this->isbnService->findOneBy($criteria);

        // Then the result should be the same ISBN
        self::assertSame($isbn, $result);
    }

    public function testFindOneByReturnsNullWhenNotFound(): void
    {
        // Given criteria that will not match any ISBN
        $criteria = ['isbn' => '978-0-00-000000-0'];

        // Then we expect the repository's findOneBy method to be called
        // Given there is no ISBN in the database
        $this->isbnRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with($criteria)
            ->willReturn(null);

        // When we call the findOneBy
        $result = $this->isbnService->findOneBy($criteria);

        // Then the result should be null
        self::assertNull($result);
    }

    public function testSave(): void
    {
        // Given an ISBN entity
        $isbn = new Isbn();
        $isbn->setIsbn('978-3-16-148410-0');

        // Then we expect the repository's save method to be called with the ISBN entity
        $this->isbnRepository
            ->expects(self::once())
            ->method('save')
            ->with($isbn);

        // When we call the save
        $this->isbnService->save($isbn);
    }
}