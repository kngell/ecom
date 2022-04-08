<?php

declare(strict_types=1);

class CacheFacade
{
    private ContainerInterface $container;

    public function __construct()
    {
    }

    /**
     * Undocumented function.
     *
     * @param string|null $cacheIdentifier
     * @param string|null $storage
     * @param array $options
     * @return CacheInterface
     */
    public function create(?string $cacheIdentifier = null, array $options = []): CacheInterface
    {
        try {
            return $this->container->make(CacheFactory::class, [
                'cacheConfig' => $this->container->make(CacheEnvironmentConfigurations::class, [
                    'cacheIdentifier' => $cacheIdentifier,
                    'fileCacheBasePath' => CACHE_PATH,
                    'maximumPathLength' => PHP_MAXPATHLEN,
                ]),
            ])->create($cacheIdentifier, $options);
        } catch (CacheException $e) {
            throw $e->getMessage();
        }
    }
}