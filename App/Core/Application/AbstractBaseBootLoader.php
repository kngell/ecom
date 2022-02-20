<?php

declare(strict_types=1);

abstract class AbstractBaseBootLoader extends Container
{
    use BootstrapTrait;

    /** @var Application */
    protected Application $application;

    /**
     * Main class constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Returns the bootstrap appplications object.
     *
     * @return Application
     */
    public function app(): Application
    {
        return $this->application;
    }

    /**
     * Return the session global variable through a static method which should make
     * accessing the global variable much more simplier
     * returns the session object.
     *
     * @return object
     */
    public static function getSession(): Object
    {
        return GlobalManager::get('session_global');
    }

    /**
     * Initialise the pass our classes to be loaded by the framework dependency
     * container using PHP Reflection.
     *
     * @param string $dependencies
     * @return mixed
     */
    public static function diGet(string $dependencies): mixed
    {
        $container = (new ContainerFactory())->create();
        if ($container) {
            return $container->make($dependencies);
        }
    }

    public function loadCache()
    {
        $cache = (new CacheFacade())->create($this->application->getCacheIdentifier(), NativeCacheStorage::class);
        if ($this->application->isCacheGlobal() === true) {
            GLobalManager::set($this->application->getGlobalCacheKey(), $cache);
        }
        return $cache;
    }

    /**
     * Compare PHP version with the core version ensuring the correct version of
     * PHP and MagmaCore framework is being used at all time in sync.
     *
     * @return void
     */
    protected function phpVersion(): void
    {
        if (version_compare($phpVersion = PHP_VERSION, $coreVersion = $this->app()->getConfig()['app']['app_version'], '<')) {
            die(sprintf('You are runninig PHP %s, but the core framework requires at least PHP %s', $phpVersion, $coreVersion));
        }
    }

    /**
     * Load the framework default enviornment configuration. Most configurations
     * can be done from inside the app.yml file.
     *
     * @return void
     */
    protected function loadEnvironment(): void
    {
        $settings = $this->app()->getConfig()['settings'];
        ini_set('default_charset', $settings['default_charset']);
        date_default_timezone_set($settings['default_timezone']);
    }

    /**
     * Returns an array of the application set providers which will be loaded
     * by the dependency container. Which uses PHP Reflection class to
     * create objects. With a key property which is defined within the yaml
     * providers file.
     *
     * @return array
     */
    protected function loadProviders(): array
    {
        return $this->app()->getContainerProviders();
    }

    /**
     * Returns the default route handler mechanism.
     *
     * @return string
     */
    protected function defaultRouteHandler(): string
    {
        return $this->make(GlobalVariables::class)->getServer('QUERY_STRING');
    }

    /**
     * Get the default session driver defined with the session.yml file.
     *
     * @return string
     */
    protected function getDefaultSessionDriver(): string
    {
        return $this->getDefaultSettings($this->app()->getSessions());
    }

    /**
     * Get the default cache driver defined with the cache.yml file.
     *
     * @return string
     */
    protected function getDefaultCacheDriver(): string
    {
        return $this->getDefaultSettings($this->app()->getCache());
    }

    /**
     * Builds the application session component and returns the configured object. Based
     * on the session configuration array.
     *
     * @return object - returns the session object
     */
    protected function loadSession(): Object
    {
        $session = (new SessionFacade($this->app()->getSessions(), $this->app()->getSessions()['session_name'], $this->app()->getSessionDriver()))->setSession();
        if ($this->application->isSessionGlobal() === true) {
            GlobalManager::set($this->application->getGlobalSessionKey(), $session);
        }
        return $session;
    }

    protected function loadRoutes()
    {
        $routes = $this->app()->getRoutes();
        $factory = $this->make(RooterFactory::class)
            ->create($this->app()->getRequest(), $this->app()->getResponse(), $routes, $this->app()->getRouter(), $this->app()->getRouteHandler());
        if (count($routes) > 0) {
            return $factory->buildRoutes($this->app()->getRoutes());
        }
    }

    protected function loadErrorHandlers(): void
    {
        error_reporting($this->app()->getErrorHandlerLevel());
        set_error_handler($this->app()->getErrorHandling()['error']);
        set_exception_handler($this->app()->getErrorHandling()['exception']);
    }

    /**
     * @return mixed
     */
    protected function loadLogger()
    {
        return (new LoggerFactory())
            ->create($this->app()->getLoggerFile(), $this->app()->getLogger(), $this->app()->getLogMinLevel(), $this->app()->getLoggerOptions());
    }

    /**
     * Load the themeBuilder component.
     *
     * @return ThemeBuilder
     */
    protected function loadThemeBuilder(): ThemeBuilder
    {
        $themeFactory = new ThemeBuilderFactory();
        $themeOptions = $this->application->getThemeBuilderOptions();
        $themeDefault = $this->application->getDefaultThemeBuilder();
        $themeBuilder = $themeFactory->create($themeDefault, $themeOptions);
        if ($themeBuilder) {
            return $themeBuilder;
        }
    }
}