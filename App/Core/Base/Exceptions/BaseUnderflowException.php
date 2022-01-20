<?php

declare(strict_types=1);

class BaseUnderflowException extends UnderflowException
{
    /**
     * Exception thrown when performing an invalid operation on an empty container,
     * such as removing an element.
     *
     * @param string $message
     * @param int $code
     * @param UnderflowException  $previous
     * @throws RuntimeException
     */
    public function __construct(string $message, int $code = 0, ?UnderflowException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
