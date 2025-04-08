<?php

namespace App\DTO;

use JsonSerializable;

/**
 * Class ErrorResponseDTO
 */
class ErrorResponseDTO implements JsonSerializable
{
    /**
     * ErrorResponseDTO constructor
     * @param int|null $code
     * @param string $message
     */
    public function __construct(
        public ?int   $code = null,
        public string $message = ''
    )
    {
    }

    /**
     * Returns serialized DTO
     * @return array{error: int|null, messge: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'error' => $this->code,
            'message' => $this->message
        ];
    }
}