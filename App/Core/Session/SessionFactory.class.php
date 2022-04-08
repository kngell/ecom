<?php

declare(strict_types=1);

class SessionFactory
{
    private ContainerInterface $container;

    /**
     * Main constructor
     *  =====================================================================.
     */
    public function __construct(private SessionStorageInterface $sessionStorage)
    {
    }

    /**
     * Create Session
     * =====================================================================.
     * @param string $sessionName
     * @param string $storageString
     * @param array $options
     * @return SessionInterface
     */
    public function create(string $sessionName) : SessionInterface
    {
        if (!$this->sessionStorage instanceof SessionStorageInterface) {
            throw new SessionStorageInvalidArgument(get_class($this->sessionStorage) . ' is not a valid session storage object!');
        }
        return $this->container->make(SessionInterface::class, [
            'storage' => $this->sessionStorage,
            'sessionIdentifier' => $sessionName,
        ]);
    }
}