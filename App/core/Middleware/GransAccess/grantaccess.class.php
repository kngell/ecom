<?php

declare(strict_types=1);
class GrantAccess
{
    public static function hasAccess($controller, $method = 'index')
    {
        $acl = Container::getInstance()->make(Files::class)->get(APP, 'acl.json');
        $current_user_acls = ['Guest'];
        $grantAccess = false;
        $session = GlobalsManager::get('global_session');
        if ($session->exists(CURRENT_USER_SESSION_NAME) && AuthManager::currentUser() != null) {
            $current_user_acls[] = 'LoggedIn';
            foreach (AuthManager::currentUser()->acls() as $a) {
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
        //dd($grantAccess);
        return $grantAccess;
    }

    public static function getMenu($menu)
    {
        $menuAry = [];
        $menu_file = file_get_contents(APP . $menu . '.json');
        $acl = json_decode($menu_file, true);
        //dd($acl);
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
                //dd($sub);

                if (!empty($sub)) {
                    $menuAry[$key] = $sub;
                }
            } else {
                //dd($val);
                if ($finalVal = self::get_link($val)) {
                    //dd($finalVal);
                    $menuAry[$key] = $finalVal;
                }
                //dd( $finalVal = self::get_link( $val ) );
            }
        }
        //dd( $menuAry );
        return $menuAry;
        //dd($menuAry);
    }

    private static function get_link($value)
    {
        $container = Container::getInstance();
        if (preg_match('/https?:\/\//', $value) == 1) {
            return $value;
        } else {
            if (self::hasAccess(get_class($container->controller), $container->method)) {
                return PROOT . $value;
            }

            return false;
        }
    }
}
