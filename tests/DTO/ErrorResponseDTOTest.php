<?php

namespace App\Tests\DTO;

use App\DTO\ErrorResponseDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class ErrorResponseDTOTest
 * @covers \App\DTO\ErrorResponseDTO
 */
final class ErrorResponseDTOTest extends TestCase
{
    public function testErrorResponseDTO(): void
    {
        // Given ErrorResponseDTO with specific values
        $dto = new ErrorResponseDTO(404, 'Not Found');

        // Then we expect correct values to be set
        self::assertSame(404, $dto->code);
        self::assertSame('Not Found', $dto->message);

        // And it should serialize correctly
        $expectedSerialized = [
            'code' => 404,
            'message' => 'Not Found',
            'errors' => null
        ];

        self::assertSame($expectedSerialized, $dto->jsonSerialize());
    }

}
