<?php

declare(strict_types=1);
class ForbidenException extends Exception
{
    protected $message = 'You do not have permission to access this page';
    protected $code = 403;
}
