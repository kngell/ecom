<?php

declare(strict_types=1);

class CacheFacade
{
    public function __construct(private CacheFactory $cacheFactory)
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
            return $this->cacheFactory->create($cacheIdentifier, $options);
        } catch (CacheException $e) {
            throw $e->getMessage();
        }
    }
}