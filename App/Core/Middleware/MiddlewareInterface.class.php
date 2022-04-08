<?php

declare(strict_types=1);

interface MiddlewareInterface
{
    /**
     * Undocumented function.
     *
     * @param object $middleware
     * @param Closure $next
     * @return void
     */
    public function middleware(Object $middleware, Closure $next);
}