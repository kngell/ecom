<?php

declare(strict_types=1);

abstract class AbstractLoggerHandler implements LoggerHandlerInterface
{
    /* @var array $options */
    private array $options;
    /* @var string $file */
    private string $file;
    /* @var string $minLevel */
    private string $minLevel;
    /* @var array $levels */
    private array $levels = [
        LogLevel::DEBUG,
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::INFO,
        LogLevel::NOTICE,
        LogLevel::WARNING,
    ];

    public function __construct()
    {
    }

    /**
     * AbstractLoggerHandler constructor.
     * @param string $file
     * @param string $minLevel
     * @param array $options
     * @return void
     */
    public function setParams(string $file, string $minLevel, array $options = []) : self
    {
        $this->options = $options;
        $this->minLevel = $minLevel;
        $this->file = $file;
        return $this;
    }

    /**
     * @return array
     */
    public function getLogOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getLogFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getMinLogLevel()
    {
        return $this->minLevel;
    }

    /**
     * @return array
     */
    public function getLogLevels(): array
    {
        return $this->levels;
    }
}