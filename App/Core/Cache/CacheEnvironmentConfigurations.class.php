<?php

declare(strict_types=1);

class CacheEnvironmentConfigurations
{
    /** @var string|null */
    protected ?string $cacheIdentifier;
    /** @var string|null */
    protected ?string $fileCacheBasePath;
    /** @var int */
    protected int $maximumPathLength;

    /**
     * Undocumented function.
     *
     * @param string|null $cacheIdentifier
     * @param string|null $fileCacheBasePath
     * @param int $maximumPathLength
     */
    public function __construct(?string $cacheIdentifier, ?string $fileCacheBasePath, ?int $maximumPathLength)
    {
        $this->cacheIdentifier = $cacheIdentifier;
        $this->fileCacheBasePath = $fileCacheBasePath;
        $this->maximumPathLength = $maximumPathLength;
    }

    /**
     * The maximum length of filenames (including path) supported by this build
     * of PHP. Available since PHP.
     *
     * @return int
     */
    public function getMaximumPathLength(): int
    {
        return $this->maximumPathLength;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function getFileCacheBasePath(): string
    {
        return $this->fileCacheBasePath;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function getCacheIdentifier(): string
    {
        return $this->cacheIdentifier;
    }
}