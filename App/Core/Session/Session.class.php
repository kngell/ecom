<?php

declare(strict_types=1);

class Session extends AbstractSession implements SessionInterface
{
    private string $sessionIdentifier;

    /**
     * Constructor.
     * ===========================================================.
     * @param SessionStorageInterface|null $storage
     * @param string $sessionIdentifier
     */
    public function __construct(private ?SessionStorageInterface $storage, string $sessionIdentifier)
    {
        if ($this->isSessionKeyValid($sessionIdentifier) === false) {
            throw new SessionStorageInvalidArgument($sessionIdentifier . ' is not a valid session name');
        }
        $this->sessionIdentifier = $sessionIdentifier;
    }

    /**
     * Return the storage object.
     * @return SessionStorageInterface|null
     */
    public function getStorage(): ?SessionStorageInterface
    {
        return $this->storage;
    }

    /**
     * Set Session
     * =====================================================================.
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws SessionException
     */
    public function set(string $key, $value): void
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            $this->storage->SetSession($key, $value);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the session storage. ' . $throwable);
        }
    }

    /**
     * Set Array Session
     * =====================================================================.
     * @param string $key
     * @param [type] $value
     * @return void
     */
    public function setArray(string $key, mixed $value): void
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            $this->storage->setArraySession($key, $value);
        } catch (\Throwable $th) {
            throw new SessionException('An error as occured when retrieving the key from Session storage. ' . $th);
        }
    }

    /**
     * Get Session
     * =====================================================================.
     * @param string $key
     * @param [type] $default
     * @return void
     */
    public function get(string $key, mixed $default = null) : mixed
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            return $this->storage->getSession($key, $default);
        } catch (\Throwable $th) {
            throw new SessionException('An exception was thrown in retrieving the key from the session storage. ' . $th);
        }
    }

    /**
     * Delete Session
     * =====================================================================.
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            $this->storage->deleteSession($key);

            return true;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * Invalidate Session
     * =====================================================================.
     * @return void
     */
    public function invalidate(): void
    {
        $this->storage->invalidateSession();
    }

    /**
     * Flush the session
     * =====================================================================.
     * @param string $key
     * @param [type] $value
     * @return void
     */
    public function flush(string $key, $value = null) : void
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            $this->storage->flushSession($key, $value);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Check for existing Session
     * =====================================================================.
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        $this->ensureSessionKeyIsValid($key);
        return $this->storage->SessionExists($key);
    }
}