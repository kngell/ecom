<?php

declare(strict_types=1);
class NotFoundException extends Exception
{
    public function __construct(string $message = 'Page not Found', int $code = 404, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}