<?php

declare(strict_types=1);

class BaseResourceNotFoundException extends Exception
{
    /**
     * Exception thrown if an argument is not of the expected type.
     *
     * @param string $message
     * @param int $code
     * @param Exception $previous
     * @throws Exception
     */
    public function __construct(string $message, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}