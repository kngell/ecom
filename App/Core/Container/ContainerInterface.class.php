<?php

declare(strict_types=1);
/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
interface ContainerInterface
{
    /**
     * Bind Classes, string into to container protected bindings
     * --------------------------------------------------------------------------------------------------.
     * @param string $abstract
     * @param Closure|string|null $concrete
     * @param bool $shared
     * @return void
     */
    public function bind(string $abstract, Closure | string | null $concrete = null, bool $shared = false): self;

    /**
     * Make a unique instance of a class or Closure
     * --------------------------------------------------------------------------------------------------.
     * @param string $abstract
     * @param Closure|string|null $concrete
     * @return self
     */
    public function singleton(string $abstract, Closure | string | null $concrete = null): self;

    /**
     * Create a container instance with existing instance
     * --------------------------------------------------------------------------------------------------.
     * @param string $abstract
     * @param mixed $instance
     * @return void
     */
    public function instance(string $abstract, mixed $instance): mixed;

    /**
     * Make and resolve dependancies
     * --------------------------------------------------------------------------------------------------.
     * @param string $abstract
     * @return mixed
     */
    public function make(string $abstract, array $args = []): mixed;

    /**
     * Returns true if the container can return an entry for the given identifier.
     * --------------------------------------------------------------------------------------------------.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has(string $id);

    /**
     * empty container
     * --------------------------------------------------------------------------------------------------.
     * @return void
     */
    public function flush(): void;

    public function getRooter();
}