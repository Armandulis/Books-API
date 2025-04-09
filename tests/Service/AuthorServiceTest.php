<?php

namespace App\Tests\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthorServiceTest
 * @covers \App\Service\AuthorService
 */
final class AuthorServiceTest extends TestCase
{
    private AuthorRepository&MockObject $authorRepository;

    private AuthorService $authorService;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up mocks
        $this->authorRepository = $this->createMock(AuthorRepository::class);

        // Set up service
        $this->authorService = new AuthorService($this->authorRepository);
    }

    public function testFindOneBy(): void
    {
        // Given author entity and criteria
        $author = new Author();
        $author->setName('Sample Author');
        $criteria = ['name' => 'Sample Author'];

        // Then we expect the repository's findOneBy method to be called with the criteria
        // Given author is in the database
        $this->authorRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with($criteria)
            ->willReturn($author);

        // When we call the findOneBy
        $result = $this->authorService->findOneBy($criteria);

        // Then the result should be the same Author entity
        self::assertSame($author, $result);
    }

    public function testFindOneByReturnsNullWhenNotFound(): void
    {
        // Given criteria that will not match any author
        $criteria = ['name' => 'Non-existent Author'];

        // Then we expect the repository's findOneBy method to be called
        // Given there is no author in DB
        $this->authorRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with($criteria)
            ->willReturn(null);

        // When we call the findOneBy
        $result = $this->authorService->findOneBy($criteria);

        // Then the result should be null
        self::assertNull($result);
    }

    public function testSave(): void
    {
        // Given author
        $author = new Author();
        $author->setName('Sample Author');

        // Then we expect the repository's save method to be called with correct Author
        $this->authorRepository
            ->expects(self::once())
            ->method('save')
            ->with($author);

        // When we call the save
        $this->authorService->save($author);
    }
}
