<?php

namespace App\DTO;

/**
 * Class BookSearchDTO
 */
class BookSearchDTO
{
    public ?string $searchType = null;
    public ?string $searchValue = null;
    public int $page = 1;
    public int $limit = 100;
}
