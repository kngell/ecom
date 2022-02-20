<?php

declare(strict_types=1);

class Application extends AbstractBaseBootLoader implements ApplicationInterface
{
    use SystemTrait;

    protected string|null $appPath;
    protected array $appConfig = [];
    protected array $session;
    protected bool $isSessionGlobal = false;
    protected ?string $globalSessionKey = null;
    protected array $cookie = [];
    protected array $cache = [];
    protected bool $isCacheGlobal = false;
    protected ?string $globalCacheKey = null;
    protected array $routes = [];
    protected array $containerProviders = [];
    protected string|null $routeHandler;
    protected string|null $newRouter;
    protected string|null $theme;
    protected ?string $newCacheDriver;
    protected string $handler;
    protected string $logFile;
    protected array $logOptions = [];
    protected string $logMinLevel;
    protected array $themeBuilderOptions = [];
    private RequestHandler $request;
    private ResponseHandler $response;
    private RooterInterface $rooter;
    private static string $appRoot;

    /**
     * Main constructor
     * =========================================================.
     * @param string $appRoot
     */
    public function __construct()
    {
        parent::__construct($this);
        $this->registerBaseBindings();
        $this->registerBaseAppSingleton();
        // $this->environment();
        // $this->errorHandler();
        $this->request = $this->make(RequestHandler::class);
        $this->response = $this->make(ResponseHandler::class);
    }

    public function run(): void
    {
        try {
            BaseConstants::load($this->app());
            $this->phpVersion();
            $this->loadErrorHandlers();
            $this->loadSession();
            $this->loadCache();
            $this->loadLogger();
            $this->loadEnvironment();
            $this->registerDatabaseAccessLayerClass();
            //$this->loadThemeBuilder();
            $this->loadRoutes()->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            $this->make(ErrorsController::class)->iniParams(ErrorsController::class, 'index', [], 'Client/')
                ->index(['exception' => $e]);
        }
    }

    /**
     * Set the project root path directory.
     *
     * @param string $rootPath
     * @return void
     */
    public function setPath(string $rootPath): self
    {
        self::$appRoot = rtrim($rootPath, '\/');
        $this->bindPathsInContainer();
        return $this;
    }

    /**
     * Return the document root path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return self::$appRoot;
    }

    public function setConst() : self
    {
        (new ConstantConfig())->ds()->appConstants(self::$appRoot);
        return $this;
    }

    /**
     * Set the default theming qualified namespace.
     *
     * @param string $theme
     * @return void
     */
    public function setTheme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Returns the theme qualified namespace.
     *
     * @return string
     */
    public function getTheme(): string
    {
        return isset($this->theme) ? $this->theme : '';
    }

    /**
     * Set the application main configuration from the project app.yml file.
     *
     * @param array $ymlApp
     * @return self
     */
    public function setConfig(array $ymlApp): self
    {
        $this->appConfig = $ymlApp;
        return $this;
    }

    /**
     * Return the application configuration as an array of data.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->appConfig;
    }

    public function getRequest() : RequestHandler
    {
        return $this->request;
    }

    public function getResponse() : ResponseHandler
    {
        return $this->response;
    }

    /**
     * Turn on global caching from public/index.php bootstrap file to make the cache
     * object available globally throughout the application using the GlobalManager object.
     * @return bool
     */
    public function isCacheGlobal(): bool
    {
        return isset($this->isCacheGlobal) && $this->isCacheGlobal === true ? true : false;
    }

    /**
     * @return string
     * @throws BaseLengthException
     */
    public function getGlobalCacheKey(): string
    {
        if ($this->globalCacheKey !== null && strlen($this->globalCacheKey) < 3) {
            throw new BaseLengthException($this->globalCacheKey . ' is invalid this needs to be more than 3 characters long');
        }
        return ($this->globalCacheKey !== null) ? $this->globalCacheKey : 'cache_global';
    }

    // public function setSession() :self
    // {
    //     self::sessionInit(true);
    //     return $this;
    // }
    /**
     * Set the application session configuration from the session.yml file else
     * load the core session configration class.
     *
     * @param array $ymlSession
     * @param ?string $newSessionDriver
     * @param bool $isGlobal defaults to false
     * @return self
     * @throws BaseInvalidArgumentException
     */
    public function setSession(array $ymlSession = [], string|null $newSessionDriver = null, bool $isGlobal = false, ?string $globalKey = null): self
    {
        $this->session = (!empty($ymlSession) ? $ymlSession : (new SessionConfig())->baseConfiguration());
        $this->newSessionDriver = ($newSessionDriver !== null) ? $newSessionDriver : $this->getDefaultSessionDriver();
        $this->isSessionGlobal = $isGlobal;
        $this->globalSessionKey = $globalKey;
        return $this;
    }

    /**
     * If session yml is set from using the setSession from the application
     * bootstrap. Use the user defined session.yml else revert to the core
     * session configuration.
     *
     * @return array
     * @throws BaseInvalidArgumentException
     */
    public function getSessions(): array
    {
        if (empty($this->session)) {
            throw new BaseInvalidArgumentException('You have no session configuration. This is required.');
        }
        return $this->session;
    }

    /**
     * Returns the default session driver from either the core or user defined
     * session configuration. Throws an exception if neither configuration
     * was found.
     *
     * @return string
     * @throws BaseInvalidArgumentException
     */
    public function getSessionDriver(): string
    {
        if (empty($this->session)) {
            throw new BaseInvalidArgumentException('You have no session configuration. This is required.');
        }
        return $this->newSessionDriver;
    }

    /**
     * Set the application cookie configuration from the session.yml file.
     *
     * @param array $ymlCookie
     * @return self
     */
    public function setCookie(array $ymlCookie): self
    {
        $this->cookie = $ymlCookie;
        return $this;
    }

    /**
     * Returns the cookie configuration array.
     *
     * @return array
     */
    public function getCookie(): array
    {
        return $this->cookie;
    }

    /**
     * Turn on global session from public/index.php bootstrap file to make the session
     * object available globally throughout the application using the GlobalManager object.
     * @return bool
     */
    public function isSessionGlobal(): bool
    {
        return isset($this->isSessionGlobal) && $this->isSessionGlobal === true ? true : false;
    }

    /**
     * @return string
     * @throws BaseLengthException
     */
    public function getGlobalSessionKey(): string
    {
        if ($this->globalSessionKey !== null && strlen($this->globalSessionKey) < 3) {
            throw new BaseLengthException($this->globalSessionKey . ' is invalid this needs to be more than 3 characters long');
        }
        return ($this->globalSessionKey !== null) ? $this->globalSessionKey : 'session_global';
    }

    /**
     * Pass the thene builder option.
     *
     * @param array $themeBuilderOptions
     * @return void
     */
    public function setThemeBuilder(array $themeBuilderOptions = []): self
    {
        //if (count($this->themeBuilderOptions) > 0) {
        $this->themeBuilderOptions = $themeBuilderOptions;
        //}
        return $this;
    }

    /**
     * Returns the theme builder options array from the yaml file.
     *
     * @return array
     */
    public function getThemeBuilderOptions(): array
    {
        return $this->themeBuilderOptions;
    }

    /**
     * Return the default theme builder library.
     *
     * @return string
     */
    public function getDefaultThemeBuilder(): ?string
    {
        if (count($this->themeBuilderOptions) > 0) {
            foreach ($this->themeBuilderOptions['libraries'] as $key => $value) {
                if (array_key_exists('default', $value)) {
                    if ($value['default'] === true) {
                        return $value['class'];
                    }
                }
            }
        }
    }

    /**
     * Set the application container providers configuration from the session.yml file.
     *
     * @param array $ymlProviders
     * @return self
     */
    public function setContainerProviders(array $ymlProviders): self
    {
        $this->containerProviders = $ymlProviders;
        return $this;
    }

    /**
     * Returns the container providers configuration array.
     *
     * @return array
     */
    public function getContainerProviders(): array
    {
        return $this->containerProviders;
    }

    /**
     * Set the application cache configuration from the session.yml file.
     * @param array $ymlCache
     * @param string|null $newCacheDriver
     * @param bool $isGloabl
     * @param string|null $globalKey
     * @return $this
     */
    public function setCache(array $ymlCache = [], ?string $newCacheDriver = null, bool $isGloabl = false, ?string $globalKey = null): self
    {
        $this->cache = (!empty($ymlCache) ? $ymlCache : (new CacheConfig())->baseConfiguration());
        $this->newCacheDriver = ($newCacheDriver !== null) ? $newCacheDriver : $this->getDefaultCacheDriver();
        $this->isCacheGlobal = $isGloabl;
        $this->globalCacheKey = $globalKey;
        return $this;
    }

    /**
     * Returns the cache configuration array.
     *
     * @return string
     */
    public function getCacheIdentifier(): string
    {
        return $this->cache['cache_name'] ?? '';
    }

    /**
     * Returns the cache configuration array.
     *
     * @return array
     */
    public function getCache(): array
    {
        return $this->cache;
    }

    /**
     * Set the application routes configuration from the session.yml file.
     *
     * @param array $ymlRoutes
     * @param string|null $routeHandler
     * @param string|null $newRouter - accepts the fully qualified namespace of new router class
     * @return self
     */
    public function setRoutes(array $ymlRoutes, string|null $routeHandler = null, string|null $newRouter = null): self
    {
        $this->routes = $ymlRoutes;
        $this->routeHandler = ($routeHandler !== null) ? $routeHandler : $this->defaultRouteHandler();
        $this->newRouter = ($newRouter !== null) ? $newRouter : '';
        return $this;
    }

    /**
     * Returns the application route configuration array.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        if (count($this->routes) < 0) {
            throw new BaseInvalidArgumentException('No routes detected within your routes.yml file');
        }
        return $this->routes;
    }

    /**
     * Returns the application route handler mechanism.
     *
     * @return string
     */
    public function getRouteHandler(): string
    {
        if ($this->routeHandler === null) {
            throw new BaseInvalidArgumentException('Please set your route handler.');
        }
        return $this->routeHandler;
    }

    /**
     * Get the new router object fully qualified namespace.
     *
     * @return string
     */
    public function getRouter(): string
    {
        if ($this->newRouter === null) {
            throw new BaseInvalidArgumentException('No new router object was defined.');
        }
        return $this->newRouter;
    }

    /**
     * Undocumented function.
     *
     * @param string $errorClass
     * @param mixed $level
     * @return self
     */
    public function setErrorHandler(array $errorHandling, mixed $level = null): self
    {
        $this->errorHandling = $errorHandling;
        $this->errorLevel = $level;
        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    public function getErrorHandling(): array
    {
        return $this->errorHandling;
    }

    public function getErrorHandlerLevel(): mixed
    {
        return $this->errorLevel;
    }

    /**
     * @param string $handler
     * @return $this
     */
    public function setLogger(string $file, string $handler, string $minLevel, array $options): self
    {
        $this->handler = $handler;
        $this->logFile = $file;
        $this->logOptions = $options;
        $this->logMinLevel = $minLevel;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogger(): string
    {
        return $this->handler;
    }

    /**
     * @return string
     */
    public function getLoggerFile(): string
    {
        return $this->logFile;
    }

    /**
     * @return array
     */
    public function getLoggerOptions(): array
    {
        return $this->logOptions;
    }

    /**
     * @return string
     */
    public function getLogMinLevel(): string
    {
        return $this->logMinLevel;
    }

    public function registerDatabaseAccessLayerClass()
    {
        $dataMapperEnvConfig = $this->singleton(DataMapperEnvironmentConfig::class, fn () => new DataMapperEnvironmentConfig(YamlFile::get('database')))->make(DataMapperEnvironmentConfig::class);
        $credentials = $dataMapperEnvConfig->getDatabaseCredentials('mysql');
        $this->singleton(DatabaseConnexionInterface::class, fn () => new DatabaseConnexion($credentials));
        $this->singleton(DataMapper::class, fn () => new DataMapper($this->make(DatabaseConnexionInterface::class)));
        $this->singleton(QueryBuilder::class, fn () => new QueryBuilder());
        $this->bind(DataMapperInterface::class, fn () => $this->make(DataMapper::class));
        $this->bind(QueryBuilderInterface::class, fn () => $this->make(QueryBuilder::class));
        $this->bind(EntityManagerFactory::class);
        $this->singleton(DataMapperFactory::class, fn () => new DataMapperFactory($dataMapperEnvConfig));
        $this->bind(RepositoryInterface::class, fn () => $this->make(Repository::class));
        $this->bind(RepositoryFactory::class, fn () => new RepositoryFactory($dataMapperEnvConfig));
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseAppSingleton()
    {
        $objs = AppHelper::singleton(self::getInstance());
        if (is_array($objs)) {
            foreach ($objs as $obj => $value) {
                $this->singleton($obj, $value);
            }
        }
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        $this->instance('path.appRoot', $this->getPath());
    }
    // /**
    //  * Set the base path for the application.
    //  *
    //  * @param  string  $basePath
    //  * @return self
    //  */
    // public function setAppRoot($appRoot) : self
    // {
    //     self::$appRoot = rtrim($appRoot, '\/');
    //     $this->bindPathsInContainer();
    //     return $this;
    // }

    // /**
    //  * Get the base path of the Laravel installation.
    //  *
    //  * @param  string  $path Optionally, a path to append to the base path
    //  * @return string
    //  */
    // public function getAppRoot($path = '')
    // {
    //     return self::$appRoot . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    // }
    // /**
    //  * Run
    //  * =========================================================.
    //  * @return self|null
    //  */
    // public function run() : ?self
    // {
    //     try {
    //         if (version_compare($phpVersion = PHP_VERSION, $coreVersion = Config::APP_MIN_VERSION, '<')) {
    //             die(sprintf('You are running php %s, but, the core framwork required at least PHP %s', $phpVersion, $coreVersion));
    //         }
    //         $this->registerDatabaseAccessLayerClass();
    //         $this->rooter = $this->make(RooterFactory::class)->create($this->request, $this->response, YamlFile::get('routes'))
    //             ->buildRoutes()
    //             ->resolve();
    //         return $this;
    //     } catch (\Exception $e) {
    //         $this->response->setStatusCode($e->getCode());
    //         $this->make(ErrorsController::class)->iniParams(ErrorsController::class, 'index', [], 'Client/')
    //             ->index(['exception' => $e]);
    //         return null;
    //     }
    // }

    // public function setConst() : self
    // {
    //     $this->make(ConstantConfig::class)->ds()->appConstants(self::$appRoot)->rootPath();
    //     return $this;
    // }

    // public function getRequest() : RequestHandler
    // {
    //     return $this->request;
    // }

    // public function getResponse() : ResponseHandler
    // {
    //     return $this->response;
    // }

    // public function registerDatabaseAccessLayerClass()
    // {
    //     $dataMapperEnvConfig = $this->singleton(DataMapperEnvironmentConfig::class, fn () => new DataMapperEnvironmentConfig(YamlFile::get('database')))->make(DataMapperEnvironmentConfig::class);
    //     $credentials = $dataMapperEnvConfig->getDatabaseCredentials('mysql');
    //     $this->singleton(DatabaseConnexionInterface::class, fn () => new DatabaseConnexion($credentials));
    //     $this->singleton(DataMapper::class, fn () => new DataMapper($this->make(DatabaseConnexionInterface::class)));
    //     $this->singleton(QueryBuilder::class, fn () => new QueryBuilder());
    //     $this->bind(DataMapperInterface::class, fn () => $this->make(DataMapper::class));
    //     $this->bind(QueryBuilderInterface::class, fn () => $this->make(QueryBuilder::class));
    //     $this->bind(EntityManagerFactory::class);
    //     $this->singleton(DataMapperFactory::class, fn () => new DataMapperFactory($dataMapperEnvConfig));
    //     $this->bind(RepositoryInterface::class, fn () => $this->make(Repository::class));
    //     $this->bind(RepositoryFactory::class, fn () => new RepositoryFactory($dataMapperEnvConfig));
    // }

    // /**
    //  * Determine if the given abstract type has been bound.
    //  *
    //  * @param  string  $abstract
    //  * @return bool
    //  */
    // public function bound($abstract)
    // {
    //     return $this->isDeferredService($abstract) || parent::bound($abstract);
    // }

    // /**
    //  * Determine if the given service is a deferred service.
    //  *
    //  * @param  string  $service
    //  * @return bool
    //  */
    // public function isDeferredService($service)
    // {
    //     return isset($this->deferredServices[$service]);
    // }

    // public function setrouteHandler(?string $url = null) : self
    // {
    //     try {
    //         $this->rooter = $this->make(RooterFactory::class)->create(YamlFile::get('routes'))
    //             ->buildRoutes()
    //             ->revolve();
    //     } catch (\Exception $e) {
    //         $this->make(ResponseHandler::class)->setStatusCode($e->getCode());
    //         $this->make(ErrorsController::class)->iniParams(ErrorsController::class, 'index', [], 'Client/')
    //             ->index(['exception' => $e]);
    //     }
    //     return $this;
    // }

    // public function handleCors()
    // {
    //     $this->make(Cors::class)->handle();
    //     return $this;
    // }

    // /**
    //  * Register the basic bindings into the container.
    //  *
    //  * @return void
    //  */
    // protected function registerBaseBindings()
    // {
    //     static::setInstance($this);
    //     $this->instance('app', $this);
    //     $this->instance(Container::class, $this);
    // }

    // // public function registerMiddleware(BaseMiddleWare $middleware) : void
    // // {
    // //     $this->middlewares[] = $middleware;
    // // }

    // /**
    //  * Register the basic bindings into the container.
    //  *
    //  * @return void
    //  */
    // protected function registerBaseAppSingleton()
    // {
    //     $objs = AppHelper::singleton(self::$instance);
    //     if (is_array($objs)) {
    //         foreach ($objs as $obj => $value) {
    //             $this->singleton($obj, $value);
    //         }
    //     }
    // }

    // /**
    //  * Bind all of the application paths in the container.
    //  *
    //  * @return void
    //  */
    // protected function bindPathsInContainer()
    // {
    //     $this->instance('path.appRoot', $this->getAppRoot());
    // }

    // /**
    //  * Environnement
    //  * =======================================================.
    //  * @return void
    //  */
    // private function environment()
    // {
    //     ini_set('default_charset', 'UTF-8');
    // }

    // /**
    //  * ErrorHandler
    //  * =======================================================.
    //  * @return void
    //  */
    // private function errorHandler() : void
    // {
    //     error_reporting(E_ALL | E_STRICT);
    //     set_error_handler('ErroHandling::errorHandler');
    //     set_exception_handler('ErroHandling::exceptionHandler');
    // }
}