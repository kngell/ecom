<?php

declare(strict_types=1);
final class Cache
{
    protected string $dirname;
    protected int $duration = 25;
    private static $instance;

    public function __construct(private Files $fileSyst)
    {
    }

    public static function getcache()
    {
        if (!isset(self::$instance)) {
            static::$instance = new static(Container::getInstance()->make(Files::class));
        }

        return static::$instance;
    }

    /**
     * Init Params
     * ==========================================================================.
     * @param string $dir
     * @param int $duration
     * @return self
     */
    public function init(string $dir = CACHE_DIR, int $duration = 0) : self
    {
        $this->dirname = $dir;
        $this->duration = $duration;

        return $this;
    }

    /**
     * Write content on file
     * ==========================================================================.
     * @param string $file
     * @param mixed $data
     * @return void
     */
    public function write(string $file, mixed $data)
    {
        $content = serialize($data);
        if ($this->fileSyst->createDir($this->dirname)) {
            return file_put_contents($this->dirname . '/' . $file, $content);
        }
    }

    /**
     * Read cached Data
     * ==========================================================================.
     * @param string $file
     * @return mixed
     */
    public function read(string $file) : mixed
    {
        if (!file_exists($this->dirname . '/' . $file)) {
            return false;
        }
        $liveTime = (time() - filemtime($this->dirname . '/' . $file)) / 60;
        if (($liveTime > $this->duration)) {
            return false;
        }
        $data = file_get_contents($this->dirname . '/' . $file);

        return unserialize($data);
    }

    /**
     * Delete File
     * ==========================================================================.
     * @param string $file
     * @return void
     */
    public function delete(string $file)
    {
        if (file_exists($this->dirname . '/' . $file)) {
            unlink($this->dirname . '/' . $file);
        }
    }

    /**
     * Clear All cached files
     * ==========================================================================.
     * @return void
     */
    public function clear()
    {
        $files = glob($this->dirname . '/*');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}