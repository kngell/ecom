<?php

declare(strict_types=1);

class BaseOutOfBoundsException extends OutOfBoundsException
{
    /**
     * Exception thrown if a value is not a valid key. This represents errors that cannot be
     * detected at compile time.
     *
     * @param string $message
     * @param int $code
     * @param OutOfBoundsException $previous
     * @throws RuntimeException
     */
    public function __construct(string $message, int $code = 0, ?OutOfBoundsException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
