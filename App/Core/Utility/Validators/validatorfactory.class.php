<?php

declare(strict_types=1);
class ValidatorFactory
{
    /**
     * Main constrctor
     * =============================================================================.
     * @param string $dispatchedUrl
     * @param array $routes
     * @param string $filePath
     */
    public function __construct(?string $dispatchedUrl = null, array $routes = [])
    {
        $this->dispatchedUrl = $dispatchedUrl;
    }

    /**
     * Create route
     * =============================================================================.
     * @param string|null $routeString
     * @return self
     */
    public function create(?string $routeString) :self
    {
        $this->router = new $routeString();
        if (!$this->router instanceof RooterInterface) {
            throw new BaseUnexpectedValueException($routeString . 'is not a valid router object!');
        }

        return $this;
    }
}
