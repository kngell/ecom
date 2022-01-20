<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CheckoutNavigationException extends BadRequestException
{
    public function __construct(string $message, int $code = 0, ?BadRequestException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
