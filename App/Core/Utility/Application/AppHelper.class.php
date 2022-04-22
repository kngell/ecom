<?php

declare(strict_types=1);

final class AppHelper
{
    public static function singleton()
    {
        return [
            'GlobalVariablesInterface' => GlobalVariables::class,
            'SessionEnvironment' => SessionEnvironment::class,
            'SessionStorageInterface' => NativeSessionStorage::class,
            'SessionInterface' => Session::class,
            'ResponseHandler' => ResponseHandler::class,
            'RequestHandler' => RequestHandler::class,
            'View' => View::class,
            'Token' => Token::class,
            'Sanitizer' => Sanitizer::class,
            'MoneyManager' => MoneyManager::class,
            'RooterInterface' => Rooter::class,
            'CookieStoreInterface' => NativeCookieStore::class,
            'CookieInterface' => Cookie::class,
            'CacheEnvironmentConfigurations' => CacheEnvironmentConfigurations::class,
            'CacheStorageInterface' => NativeCacheStorage::class,
            'CacheInterface' => Cache::class,
            'LoggerHandlerInterface' => NativeLoggerHandler::class,
            'LoggerInterface' => Logger::class,
            'LoggerFactory' => LoggerFactory::class,
            'LoginForm' => LoginForm::class,
            'RegisterForm' => RegisterForm::class,
            'DispatcherInterface' => Dispatcher::class,
            'ViewInterface' => View::class,
            'MailerInterface' => Mailer::class,
            'DataMapperEnvironmentConfig' => DataMapperEnvironmentConfig::class,
            'DataMapperInterface' => DataMapper::class,
            'QueryBuilderInterface' => QueryBuilder::class,
            'DatabaseConnexionInterface' => DatabaseConnexion::class,
            'CrudInterface' => Crud::class,
        ];
    }

    public static function dataAccessLayerClass()
    {
        return[
            'EntityManagerInterface' => EntityManager::class,
            'RepositoryInterface' => Repository::class,
            'DataAccessLayerManager' => DataAccessLayerManager::class,
            'QueryParamsInterface' => QueryParamsInterface::class,
        ];
    }

    public static function bindedClass()
    {
        return [
            'QueryParamsInterface' => QueryParams::class,
        ];
    }
}