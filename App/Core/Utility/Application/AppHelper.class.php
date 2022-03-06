<?php

declare(strict_types=1);

final class AppHelper
{
    public static function singleton(Application $app)
    {
        return [
            'ConstantConfig' => fn () => new ConstantConfig(),
            'GlobalVariables' => fn () => new GlobalVariables(),
            'SessionConfig' => fn () => new SessionConfig(),
            'SessionFacade' => fn () => new SessionFacade(),
            'SessionEnvironment' => fn () => new SessionEnvironment(),
            'NativeSessionStorage' => fn () => new NativeSessionStorage($app->make(GlobalVariables::class)),
            'SessionStorageInterface' => fn () => $app->make(NativeSessionStorage::class),
            'SessionFactory' => fn () => new SessionFactory($app->make(SessionStorageInterface::class)),
            'SessionManager' => fn () => new SessionManager($app->make(SessionFactory::class)),
            'SessionInterface' => fn () => new Session($app->make(SessionStorageInterface::class)),
            'Sanitizer' => fn () => new Sanitizer(),
            'ResponseHandler' => fn () => new ResponseHandler(),
            'RequestHandler' => fn () => new RequestHandler($app->make(Sanitizer::class)),
            'View' => fn () => new View(),
            'Token' => fn () => new Token(),
            'MoneyManager' => fn () => new MoneyManager(),
            'ControllerHelper' => fn () => new ControllerHelper(),
            'UsersManager' => fn () => new UsersManager(),
            'UsersEntity' => fn () => new UsersEntity(),
            'ModelHelper' => fn () => new ModelHelper(),
            'RooterHelper' => fn () => new RooterHelper(),
            'RooterInterface' => fn () => new Rooter($app->make(RooterHelper::class)),
            'RooterFactory' => fn () => new RooterFactory($app->make(RooterInterface::class), $app->make(View::class)),
            'CookieStoreInterface' => fn () => new NativeCookieStore($app->make(CookieEnvironment::class)),
            'CookieInterface' => fn () => new Cookie($app->make(CookieStoreInterface::class)),
            'CacheEnvironmentConfigurations' => fn () => new CacheEnvironmentConfigurations(),
            'CacheStorageInterface' => fn () => new NativeCacheStorage(),
            'CacheFactory' => fn () => new CacheFactory($app->make(CacheEnvironmentConfigurations::class), $app->make(CacheStorageInterface::class)),
            'CacheFacade' => fn () => new CacheFacade($app->make(CacheFactory::class)),
            'CacheInterface' => fn () => new Cache(),
            'LoggerHandlerInterface' => fn () => new NativeLoggerHandler(),
            'LoggerInterface' => fn () => new Logger($app->make(LoggerHandlerInterface::class)),
            'LoggerFactory' => fn () => new LoggerFactory($app->make(LoggerInterface::class)),
            'CoreError' => fn () => new CoreError(),
            'LoginForm' => fn () => new LoginForm($app->make(CoreError::class), $app->make(RequestHandler::class), $app->make(Token::class)),
            'RegisterForm' => fn () => new RegisterForm($app->make(CoreError::class), $app->make(RequestHandler::class), $app->make(Token::class)),

        ];
    }

    public static function dataAccessLayerClass(Application $app)
    {
        return[
            'DataMapperEnvironmentConfig' => fn () => new DataMapperEnvironmentConfig(),
            'DatabaseConnexionInterface' => fn () => new DatabaseConnexion(),
            'DataMapperInterface' => fn () => new DataMapper($app->make(DatabaseConnexionInterface::class)),
            'QueryBuilderInterface' => fn () => new QueryBuilder(),
            'EntityManagerFactory' => fn () => new EntityManagerFactory($app->make(DataMapperInterface::class), $app->make(QueryBuilderInterface::class)),
            'DataMapperFactory' => fn () => new DataMapperFactory(),
            'EntityManagerInterface' => fn () => new EntityManager($app->make(CrudInterface::class)),
            'RepositoryInterface' => fn () => new Repository($app->make(EntityManagerInterface::class)),
            'RepositoryFactory' => fn () => new RepositoryFactory(),
            'DataAccessLayerManager' => fn () => new DataAccessLayerManager($app->make(DataMapperEnvironmentConfig::class)),
            'CrudInterface' => fn () => new Crud(),
        ];
    }
}