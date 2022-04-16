<?php

declare(strict_types=1);

final class AppHelper
{
    public static function singleton()
    {
        return [
            'ConstantConfig' => fn () => new ConstantConfig(),
            'GlobalVariablesInterface' => fn () => new GlobalVariables,
            'SessionConfig' => fn () => new SessionConfig(),
            'SessionFacade' => SessionFacade::class,
            'SessionEnvironment' => SessionEnvironment::class,
            'SessionStorageInterface' => NativeSessionStorage::class,
            'SessionFactory' => SessionFactory::class,
            'SessionManager' => SessionManager::class,
            'SessionInterface' => Session::class,
            'Sanitizer' => fn () => new Sanitizer(),
            'ResponseHandler' => fn () => new ResponseHandler(),
            'RequestHandler' => RequestHandler::class,
            'View' => View::class,
            'Token' => Token::class,
            'MoneyManager' => fn () => new MoneyManager(),
            'ControllerHelper' => ControllerHelper::class,
            'UsersManager' => UsersManager::class,
            'UsersEntity' => fn () => new UsersEntity(),
            'ModelHelper' => fn () => new ModelHelper(),
            'RooterHelper' => fn () => new RooterHelper(),
            'RooterInterface' => Rooter::class,
            'RooterFactory' => RooterFactory::class,
            'CookieStoreInterface' => NativeCookieStore::class,
            'CookieInterface' => Cookie::class,
            'CacheEnvironmentConfigurations' => CacheEnvironmentConfigurations::class,
            'CacheStorageInterface' => NativeCacheStorage::class,
            'CacheFactory' => CacheFactory::class,
            'CacheFacade' => CacheFacade::class,
            'CacheInterface' => Cache::class,
            'LoggerHandlerInterface' => NativeLoggerHandler::class,
            'LoggerInterface' => Logger::class,
            'LoggerFactory' => LoggerFactory::class,
            'CoreError' => fn () => new CoreError(),
            'LoginForm' => LoginForm::class,
            'RegisterForm' => RegisterForm::class,
            'DispatcherInterface' => Dispatcher::class,
            'ViewInterface' => View::class,
            'MailerInterface' => Mailer::class,

        ];
    }

    public static function dataAccessLayerClass()
    {
        return[
            'DataMapperEnvironmentConfig' => DataMapperEnvironmentConfig::class,
            'DatabaseConnexionInterface' => DatabaseConnexion::class,
            'DataMapperInterface' => DataMapper::class,
            'QueryBuilderInterface' => QueryBuilder::class,
            'EntityManagerFactory' => EntityManagerFactory::class,
            'DataMapperFactory' => DataMapperFactory::class,
            'EntityManagerInterface' => EntityManager::class,
            'RepositoryInterface' => Repository::class,
            'RepositoryFactory' => RepositoryFactory::class,
            'DataAccessLayerManager' => DataAccessLayerManager::class,
            'CrudInterface' => Crud::class,
        ];
    }

    public static function bindedClass()
    {
        return [
            'QueryParamsInterface' => QueryParams::class,
        ];
    }
}