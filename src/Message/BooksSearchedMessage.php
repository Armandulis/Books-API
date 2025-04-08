<?php

namespace App\Message;

use App\DTO\BookSearchDTO;

/**
 * Class BooksSearchedMessage
 */
class BooksSearchedMessage
{
    /**
     * BooksSearchedMessage constructor
     * @param BookSearchDTO $bookSearchDTO
     * @param int $attemptNumber
     */
    public function __construct(
        private readonly BookSearchDTO $bookSearchDTO,
        private readonly int           $attemptNumber = 1,
    )
    {
    }

    /**
     * Returns book search DTO
     * @return BookSearchDTO
     */
    public function getBookSearchDTO(): BookSearchDTO
    {
        return $this->bookSearchDTO;
    }

    /**
     * Returns message attempt
     * @return int
     */
    public function getAttemptNumber(): int
    {
        return $this->attemptNumber;
    }
}