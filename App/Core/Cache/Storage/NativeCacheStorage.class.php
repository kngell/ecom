<?php

declare(strict_types=1);

class NativeCacheStorage extends AbstractCacheStorage
{
    use CacheStorageTrait;

    /**
     * Undocumented function.
     */
    public function __construct()
    {
    }

    public function setParams(CacheEnvironmentConfigurations $envConfigurations, array $options = []) : self
    {
        parent::setParams($envConfigurations, $options);
        return $this;
    }

    /**
     * Saves data in a cache file.
     *
     * @param string $key
     * @param string $value The data to be stored
     * @param int|null $ttl
     * @return void
     * @throws CacheException if the directory does not exist or is not writable
     *                        or exceeds the maximum allowed path length, or if no
     *                        cache frontend has been set.
     * @api
     */
    public function setCache(string $key, string $value, ?int $ttl = null): void
    {
        $this->isCacheValidated($key);
        $cacheEntryPathAndFilename = $this->cacheEntryPathAndFilename($key);
        $result = $this->writeCacheFile($cacheEntryPathAndFilename, $value);
        if ($result !== false) {
            return;
        }
        throw new CacheException('The cache file "' . $cacheEntryPathAndFilename . '" could not be written.', 0);
    }

    /**
     * @inheritDoc
     * @param string $key
     * @return string|bool
     */
    public function getCache(string $key): string|bool
    {
        $this->isCacheValidated($key, false);
        $cacheEntryPathAndFilename = $this->cacheEntryPathAndFilename($key);
        if (!file_exists($cacheEntryPathAndFilename)) {
            return false;
        }

        return $this->readCacheFile($cacheEntryPathAndFilename);
    }

    /**
     * @inheritDoc
     * @param string $key
     * @return bool
     */
    public function hasCache(string $key): bool
    {
        $this->isCacheValidated($key, false);
        return file_exists($this->cacheEntryPathAndFilename($key));
    }

    /**
     * @inheritDoc
     * @param string $key
     */
    public function removeCache(string $key): bool
    {
        $this->isCacheValidated($key);
        $cacheEntryPathAndFilename = $this->cacheEntryPathAndFilename($key);
        for ($i = 0; $i < 3; $i++) {
            $result = $this->tryRemoveWithLock($cacheEntryPathAndFilename);
            if ($result === true) {
                clearstatcache(true, $cacheEntryPathAndFilename);
                return true;
            }
            usleep(rand(10, 500));
        }

        return false;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function flush(): void
    {
        Files::emptyDirectoryRecursively($this->cacheDirectory);
    }

    /**
     * @inheritDoc
     */
    public function collectGarbage(): void
    {
    }
}