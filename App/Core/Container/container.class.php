<?php

declare(strict_types=1);

class Container implements ContainerInterface
{
    /** @var mixed */
    protected static $instance;

    /** @var object[] */
    protected array $instances = [];

    /** @var array[] */
    protected array $bindings = [];

    /**
     * All of the registered rebound callbacks.
     *
     * @var array[]
     */
    protected $reboundCallbacks = [];

    private ReflectionClass $reflector;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function setInstance(?Application $container = null)
    {
        return static::$instance = $container;
    }

    /**
     * Get container instance
     * ===============================================.
     * @return mixed
     */
    final public static function getInstance() : mixed
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Check is Container as singleton
     *  ==============================================.
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->instances[$id]);
    }

    public function bind(string $abstract, Closure | string | null $concrete = null, bool $shared = false): self
    {
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared,
        ];

        return $this;
    }

    public function singleton(string $abstract, Closure | string | null $concrete = null): self
    {
        return $this->bind($abstract, $concrete, true);
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string  $abstract
     * @param  mixed  $instance
     * @return mixed
     */
    public function instance(string $abstract, mixed $instance) : mixed
    {
        $isBound = $this->bound($abstract);
        $this->instances[$abstract] = $instance;
        if ($isBound) {
            $this->rebound($abstract);
        }
        return $instance;
    }

    /**
     * Determine if the given abstract type has been bound.
     * =========================================================================================.
     * @param  string  $abstract
     * @return bool
     */
    public function bound($abstract)
    {
        return isset($this->bindings[$abstract]) ||
               isset($this->instances[$abstract]);
    }

    /**
     * Make object
     * =========================================================================================.
     * @param string $abstract
     * @return mixed
     */
    public function make(string $abstract, array $args = []): mixed
    {
        if ($this->has($abstract)) {
            return $this->instances[$abstract];
        }
        $concrete = $this->bindings[$abstract]['concrete'] ?? $abstract;
        if ($concrete instanceof Closure || $concrete === $abstract) {
            $object = $this->build($concrete, $args);
        } else {
            $object = $this->make($concrete, $args);
        }
        if (isset($this->bindings[$abstract]) && $this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = $object;
        }
        return $object;
    }

    /**
     * Build Objects
     * =========================================================================================.
     * @param Closure|string $concrete
     * @return mixed
     */
    public function build(Closure | string $concrete, array $args = []): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }
        try {
            $this->reflector = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new BindingResolutionException("Target class [$concrete] does not exist.", 0, $e);
        }
        if (!$this->reflector->isInstantiable()) {
            throw new BindingResolutionException("Target [$concrete] is not instantiable.");
        }
        $constructor = $this->reflector->getConstructor();
        if ($constructor === null) {
            $obj = $this->reflector->newInstance();
            return $this->objectWithContainer($obj);
        }
        $dependencies = $constructor->getParameters();
        $instances = $this->resolveDependencies($dependencies, $args);
        $obj = $this->reflector->newInstanceArgs($instances);
        return $this->objectWithContainer($obj);
    }

    public function flush(): void
    {
        $this->bindings = [];
        $this->instances = [];
    }

    public function getRooter()
    {
        return $this->rooter;
    }

    /**
     * Resolve Dependencies
     * =========================================================================================.
     * @param array $dependencies
     * @return array
     */
    protected function resolveDependencies(array $dependencies, array $args = []): array
    {
        $results = [];
        foreach ($dependencies as $dependency) {
            // This is a much simpler version of what Laravel does
            $type = $dependency->getType(); // ReflectionType|null
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                if ($dependency->isDefaultValueAvailable() || !empty($args)) {
                    if ($dependency->isDefaultValueAvailable() && !array_key_exists($dependency->name, $args)) {
                        $results[] = $dependency->getDefaultValue();
                    }
                    if (!empty($args)) {
                        $results[] = $args[$dependency->name];
                    }
                } else {
                    throw new DependencyHasNoDefaultValueException('Sorry cannot resolve class dependency ' . $dependency->name);
                }
            } elseif (!$this->reflector->isUserDefined()) {
                $results[] = $this->make($dependency->name);
            } else {
                if (!empty($args)) {
                    $results[] = $args[$dependency->name];
                } else {
                    $results[] = $this->make($type->getName());
                }
            }

            // else {
            //     throw new BindingResolutionException("Unresolvable dependency resolving [$dependency] in class {$dependency->getDeclaringClass()->getName()}");
            // }
        }
        return $results;
    }

    /**
     * Fire the "rebound" callbacks for the given abstract type.
     *
     * @param  string  $abstract
     * @return void
     */
    protected function rebound($abstract)
    {
        $instance = $this->make($abstract);

        foreach ($this->getReboundCallbacks($abstract) as $callback) {
            call_user_func($callback, $this, $instance);
        }
    }

    /**
     * Get the rebound callbacks for a given type.
     *
     * @param  string  $abstract
     * @return array
     */
    protected function getReboundCallbacks($abstract)
    {
        return $this->reboundCallbacks[$abstract] ?? [];
    }

    /**
     * Set Container into created object
     * =========================================================================================.
     * @param ReflectionClass $reflector
     * @param object $obj
     * @return void
     */
    protected function objectWithContainer(Object $obj)
    {
        if ($this->reflector->hasProperty('container')) {
            $reflectionContainer = $this->reflector->getProperty('container');
            $reflectionContainer->setAccessible(true);
            if (!$reflectionContainer->isInitialized($obj)) {
                $reflectionContainer->setValue($obj, $this);
            }
        }
        return $obj;
    }
}