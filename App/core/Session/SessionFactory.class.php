<?php

declare(strict_types=1);

class SessionFactory
{
    private ContainerInterface $container;
    private SessionStorageInterface $sessionStorage;

    /**
     * Main constructor
     *  =====================================================================.
     */
    public function __construct(SessionStorageInterface $sessionStorage)
    {
        $this->sessionStorage = $sessionStorage;
    }

    /**
     * Create Session
     * =====================================================================.
     * @param string $sessionName
     * @param string $storageString
     * @param array $options
     * @return SessionInterface
     */
    public function create(string $sessionName, array $options = []) : SessionInterface
    {
        $this->sessionStorage->initOptions($options);
        if (!$this->sessionStorage instanceof SessionStorageInterface) {
            throw new SessionStorageInvalidArgument(get_class($this->sessionStorage) . ' is not a valid session storage object!');
        }
        return Container::getInstance()->bind(SessionInterface::class, fn () => new Session($this->sessionStorage, $sessionName))->make(SessionInterface::class);
    }
}