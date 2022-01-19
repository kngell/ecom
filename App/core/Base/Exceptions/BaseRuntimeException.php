<?php

declare(strict_types=1);

class BaseRuntimeException extends RuntimeException
{
    /**
     * Exception thrown if an error which can only be found on runtime occurs.
     *
     * @param string $message
     * @param int $code
     * @param RuntimeException $previous
     * @throws Exception
     */
    public function __construct(string $message, int $code = 0, ?RuntimeException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
