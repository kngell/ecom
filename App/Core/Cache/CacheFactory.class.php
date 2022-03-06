<?php

declare(strict_types=1);

class CacheFactory
{
    /** @var object */
    protected Object $envConfigurations;

    public function __construct(private CacheEnvironmentConfigurations $cacheConfig, private NativeCacheStorage $storage)
    {
    }

    /**
     * Factory create method which create the cache object and instantiate the storage option
     * We also set a default storage options which is the NativeCacheStorage. So if the second
     * argument within the create method is left to null. Then the default cache storage object
     * will be created and all necessary argument injected within the constructor.
     *
     * @param string|null $cacheIdentifier
     * @param string|null $storage
     * @param array $options
     * @return CacheInterface
     */
    public function create(?string $cacheIdentifier = null, array $options = []): CacheInterface
    {
        $this->envConfigurations = $this->cacheConfig->setParams($cacheIdentifier, CACHE_PATH);
        $storageObject = $this->storage->setParams($this->envConfigurations, $options);
        if (!$storageObject instanceof CacheStorageInterface) {
            throw new cacheInvalidArgumentException('"' . $this->storage::class . '" is not a valid cache storage object.', 0);
        }
        return Container::getInstance()->make(CacheInterface::class)->setParams($cacheIdentifier, $storageObject, $options);
    }
}