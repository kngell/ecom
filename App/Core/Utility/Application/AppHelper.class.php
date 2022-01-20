<?php

declare(strict_types=1);

final class AppHelper
{
    public static function singleton(Application $app)
    {
        return [
            'ConstantConfig' => fn () => new ConstantConfig(),
            'GlobalVariables' => fn () => new GlobalVariables(),
            'NativeSessionStorage' => fn () => new NativeSessionStorage($app->make(GlobalVariables::class)),
            'SessionStorageInterface' => fn () => $app->make(NativeSessionStorage::class),
            'SessionFactory' => fn () => new SessionFactory($app->make(SessionStorageInterface::class)),
            'SessionManager' => fn () => new SessionManager($app->make(SessionFactory::class)),
            'Sanitizer' => fn () => new Sanitizer(),
            'ResponseHandler' => fn () => new ResponseHandler(),
            'RequestHandler' => fn () => new RequestHandler($app->make(Sanitizer::class)),
            'RooterHelper' => fn () => new RooterHelper(),
            'RooterInterface' => fn () => new Rooter($app->make(RooterHelper::class)),
            'RooterFactory' => fn () => new RooterFactory($app->make(RooterInterface::class)),
            'View' => fn () => new View(),
            'Token' => fn () => new Token(),
            'MoneyManager' => fn () => new MoneyManager(),
            'ControllerHelper' => fn () => new ControllerHelper(),
            'UsersManager' => fn () => new UsersManager(),
            'UsersEntity' => fn () => new UsersEntity(),
            'ModelHelper' => fn () => new ModelHelper(),

        ];
    }
}