<?php

declare(strict_types=1);

class NativeLoggerHandler extends AbstractLoggerHandler
{
    use LoggerTrait;

    private string $file;

    public function __construct(string $file, string $minLevel, array $options)
    {
        parent::__construct($file, $minLevel, $options);
        if (!file_exists($this->getLogFile())) {
            if (!touch($this->getLogFile())) {
                throw new LoggerHandlerInvalidArgumentException('Log file ' . $this->getLogFile() . ' can not be created.');
            }
        }
        if (!is_writable($this->getLogFile())) {
            throw new LoggerHandlerInvalidArgumentException('Log file ' . $this->getLogFile() . ' is not writable.');
        }
    }

    /**
     * NativeLoggerHandler constructor.
     * @param string $file
     * @param string $minLevel
     * @param array $options
     * @return void
     */
    public function setParams() : self
    {
        return $this;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function write(string $level, string $message, array $context = []): void
    {
        if (!$this->logLevelReached($level)) {
            return;
        }
        $line = $this->format($level, $message, $context);
        file_put_contents($this->getLogFile(), $line, FILE_APPEND | LOCK_EX);
    }
}