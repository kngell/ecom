<?php

declare(strict_types=1);

class Singleton
{
    /**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass.
     */
    private static array $instance = [];

    /**
     * Cloning and unserialization are not permitted for singletons.
     */
    final protected function __clone()
    {
    }

    /**
     * @throws \Exception
     */
    final public function __wakeup()
    {
        throw new \Exception('Cannot unserialize a singleton.');
    }

    final public static function getInstance()
    {
        $subClass = static::class;
        if (!isset(self::$instance[$subClass])) {
            self::$instance[$subClass] = new static();
        }
        return self::$instance[$subClass];
    }
}