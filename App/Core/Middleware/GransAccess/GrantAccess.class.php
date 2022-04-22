<?php

declare(strict_types=1);
class GrantAccess
{
    private static ContainerInterface $container;
    private static ModelInterface $loggedInUser;
    private static array $aclGroup;
    private static $instance;

    public function __construct()
    {
        self::$aclGroup = AuthManager::currentUser()->acls();
        self::$container = Container::getInstance();
        self::$loggedInUser = AuthManager::currentUser();
    }

    final public static function getInstance() : mixed
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getMenu($menu)
    {
        $menuAry = [];
        $menu_file = file_get_contents(APP . $menu . '.json');
        $acl = json_decode($menu_file, true);
        foreach ($acl as $key => $val) {
            if (is_array($val)) {
                $sub = [];
                foreach ($val as $k => $v) {
                    if ($k == 'separator' && !empty($sub)) {
                        $sub[$k] = '';
                        continue;
                    }
                    if ($finalVal = self::get_link($v)) {
                        $sub[$k] = $finalVal;
                    }
                }
                if (!empty($sub)) {
                    $menuAry[$key] = $sub;
                }
            } else {
                if ($finalVal = self::get_link($val)) {
                    $menuAry[$key] = $finalVal;
                }
            }
        }
        return $menuAry;
    }

    private function hasAccess($controller, $method = 'index')
    {
        $acl = self::$container->make(FilesSyst::class)->get(APP, 'acl.json');
        $current_user_acls = ['Guest'];
        $grantAccess = false;
        $session = self::$container->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME) && self::$loggedInUser != null) {
            $current_user_acls[] = 'LoggedIn';
            foreach (self::$aclGroup as $a) {
                $current_user_acls[] = $a;
            }
        }
        foreach ($current_user_acls as $level) {
            if (array_key_exists($level, $acl) && array_key_exists($controller, $acl[$level])) {
                if (in_array($method, $acl[$level][$controller]) || in_array('*', $acl[$level][$controller])) {
                    $grantAccess = true;
                    break;
                }
            }
        }
        // Checck for denied
        foreach ($current_user_acls as $level) {
            $denied = $acl[$level]['denied'];
            if (!empty($denied) && array_key_exists($controller, $denied) && in_array($method, $denied[$controller])) {
                $grantAccess = false;
                break;
            }
        }
        return $grantAccess;
    }

    private function get_link($route) : bool|string
    {
        if (preg_match('/https?:\/\//', $route) == 1) {
            return $route;
        } else {
            if ($this->hasAccess(get_class(self::$container->make('controller')), self::$container->make('method'))) {
                return $route;
            }
            return false;
        }
    }
}