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
     */
    public function __construct(
        private readonly BookSearchDTO $bookSearchDTO,
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
}