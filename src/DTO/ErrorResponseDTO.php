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
     * @param array|null $errors
     */
    public function __construct(
        public ?int   $code = null,
        public string $message = '',
        public ?array $errors = null,
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
            'code' => $this->code,
            'message' => $this->message,
            'errors' => $this->errors
        ];
    }
}