<?php

declare(strict_types=1);

class SelectPathMiddleware extends BeforeMiddleware
{
    public function __construct()
    {
    }

    /**
     * Show User Comment.
     *
     * @param object $middleware
     * @param Closure $next
     * @return mixed
     */
    public function middleware(object $object, Closure $next) : mixed
    {
        $object->createView();
        if (str_contains($object->getFilePath(), 'Client' . DS)) {
            $object->view()->addProperties([
                'settings' => $object->getSettings(),
            ]);
        } elseif (str_contains($object->getFilePath(), 'Backend' . DS)) {
            $object->view_instance->siteTitle("K'nGELL Administration");
            $object->view_instance->layout('admin');
        }
        return $next($object);
    }
}