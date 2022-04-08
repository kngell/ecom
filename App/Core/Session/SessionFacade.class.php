<?php

declare(strict_types=1);

final class SessionFacade
{
    /** @var string|null - a string which identifies the current session */
    protected ?string $sessionIdentifier;

    /** @var SessionStorageInterface|null - the namespace reference to the session storage type */
    protected ?string $storage;

    /** @var object - the session environment object */
    protected Object $sessionEnvironment;
    private ContainerInterface $container;

    /**
     * Main session facade class which pipes the properties to the method arguments.
     *
     * @param array|null $sessionEnvironment - expecting a session.yaml configuration file
     * @param string|null $sessionIdentifier
     * @param null|string $storage - optional defaults to nativeSessionStorage
     */
    public function __construct(?array $sessionEnvironment = null, string|null $sessionIdentifier = null, string|null $storage = null)
    {
        $this->container = Container::getInstance();
        $this->sessionEnvironment = $this->container->make(SessionEnvironment::class, [
            'sessionConfig' => $sessionEnvironment,
        ]);
        $this->sessionIdentifier = $sessionIdentifier;
        $this->storage = $storage;
    }

    /**
     * Initialize the session component and return the session object. Also stored the
     * session object within the global manager. So session can be fetch throughout
     * the application by using the GlobalManager::get('session_global') to get
     * the session object.
     *
     * @return SessionInterface
     * @throws GlobalsManagerExceptions
     */
    public function setSession(): SessionInterface
    {
        try {
            return $this->container->make(SessionFactory::class, [
                'sessionStorage' => $this->container->make(SessionStorageInterface::class, [
                    'gv' => $this->container->make(GlobalVariablesInterface::class),
                    'options' => $this->sessionEnvironment->getConfig(),
                ]),
            ])->create($this->sessionIdentifier);
        } catch (SessionException $e) {
            throw new SessionException($e->getMessage());
        }
    }
}