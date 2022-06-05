<?php

declare(strict_types=1);
class GrantAccess
{
    private ContainerInterface $container;
    private bool $loggedInUser;
    private array $aclGroup;
    private static $instance;
    private SessionInterface $session;
    private FilesSystemInterface $fileSystem;
    private array $acl;

    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->session = $this->container->make(SessionInterface::class);
        $this->aclGroup = AuthManager::acls();
        $this->loggedInUser = AuthManager::isLoggedIn();
        $this->fileSystem = $this->container->make(FilesSystemInterface::class);
        $this->acl = $this->fileSystem->get(APP, 'acl.json', true);
    }

    final public static function getInstance() : mixed
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getMenu(string $menu, ?string $submenu = null)
    {
        $menuAry = [];
        $menu = $this->fileSystem->get(APP, $menu . '.json');
        $menu = $this->accountIsVerified($menu);
        foreach ($menu as $key => $val) {
            if (is_array($val)) {
                $sub = [];
                foreach ($val as $k => $v) {
                    if ($k == 'separator' && !empty($sub)) {
                        $sub[$k] = '';
                        continue;
                    }
                    if ($finalVal = $this->get_link($v)) {
                        $sub[$k] = $finalVal;
                    }
                }
                if (!empty($sub)) {
                    $menuAry[$key] = $sub;
                }
            } else {
                if ($finalVal = $this->get_link($val)) {
                    $menuAry[$key] = $finalVal;
                }
            }
        }
        return $submenu !== null && isset($menuAry[$submenu]) ? $menuAry[$submenu] : $menuAry;
    }

    private function accountIsVerified(array $acl) : array
    {
        if ($this->session->exists(CURRENT_USER_SESSION_NAME)) {
            $session_values = $this->session->get(CURRENT_USER_SESSION_NAME);
            if (array_key_exists('verified', $session_values)) {
                if ($session_values['verified'] > 0) {
                    if (array_key_exists('Confirmez votre compte', $acl['log_reg_menu'])) {
                        unset($acl['log_reg_menu']['Confirmez votre compte']);
                    }
                }
            }
        }
        return $acl;
    }

    private function hasAccess($controller, $method = 'index')
    {
        $current_user_acls = ['Guest'];
        $grantAccess = false;
        if ($this->session->exists(CURRENT_USER_SESSION_NAME) && $this->loggedInUser != null) {
            $current_user_acls[] = 'LoggedIn';
            foreach ($this->aclGroup as $a) {
                $current_user_acls[] = $a;
            }
        }
        foreach ($current_user_acls as $level) {
            if (array_key_exists($level, $this->acl) && array_key_exists($controller, $this->acl[$level])) {
                if (in_array($method, $this->acl[$level][$controller]) || in_array('*', $this->acl[$level][$controller])) {
                    $grantAccess = true;
                    break;
                }
            }
        }
        // Checck for denied
        foreach ($current_user_acls as $level) {
            $denied = $this->acl[$level]['denied'];
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
            if ($this->hasAccess($this->container->make('controller'), $this->container->make('method'))) {
                return $route;
            }
            return false;
        }
    }
}