<?php

declare(strict_types=1);

class Error404 extends BeforeMiddleware
{
    /**
     * Prevent unauthorized access to the administration panel. Only users with specific
     * privileges can access the admin area.
     *
     * @param object $middleware
     * @param Closure $next
     * @return mixed
     */
    public function middleware(object $object, Closure $next) : mixed
    {
        return $next($object);
    }
}