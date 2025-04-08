<?php

namespace App\Enum;

/**
 * Class BookSearchTypeEnum
 */
enum BookSearchTypeEnum: string
{
    case TITLE = 'title';
    case AUTHOR = 'author';
    case ISBN = 'isbn';
}
