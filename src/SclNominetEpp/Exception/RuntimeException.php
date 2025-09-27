<?php

namespace SclNominetEpp\Exception;

/**
 * RuntimeException
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    /**
     * Create and throw a RuntimeException for unexpected result codes.
     *
     * @param integer $code    The unexpected result code.
     * @param string  $message The error message.
     *
     * @throws RuntimeException Always throws this exception.
     * @return void
     */
    public static function unexpectedResultCode(int $code, string $message): void
    {
        throw new self("Unexpected result code: $code, message: $message");
    }
}
