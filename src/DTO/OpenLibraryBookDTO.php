<?php

namespace App\DTO;

/**
 * Class OpenLibraryBookDTO
 */
class OpenLibraryBookDTO
{
    public ?array $authorKeys = null;
    public ?array $authorNames = null;
    public ?string $coverEditionKey = null;
    public ?int $coverI = null;
    public ?int $editionCount = null;
    public ?int $firstPublishYear = null;
    public ?bool $hasFulltext = null;
    public ?array $ia = null;
    public ?string $iaCollectionS = null;
    public ?string $key = null;
    public ?array $language = null;
    public ?string $lendingEditionS = null;
    public ?string $lendingIdentifierS = null;
    public ?bool $publicScanB = null;
    public ?string $title = null;
}