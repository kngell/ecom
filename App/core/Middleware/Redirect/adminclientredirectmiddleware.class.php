<?php

declare(strict_types=1);

class AdminClientRedirectMiddleware
{
    public static function redirecTo($controller) : ?string
    {
        switch ($controller) {
            case in_array($controller, YamlFile::get('controller')['client']):
                return 'Client' . DS;
                break;
            case in_array($controller, YamlFile::get('controller')['backend']):
                return 'Backend' . DS;
                break;
            case in_array($controller, YamlFile::get('controller')['ajax']):
                return 'Ajax' . DS;
                break;
            case in_array($controller, YamlFile::get('controller')['auth']):
                return 'Auth' . DS;
                break;
            case in_array($controller, YamlFile::get('controller')['asset']):
                return 'Asset' . DS;
                break;
            default:
                return '';
                break;
        }
    }
}
