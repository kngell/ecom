<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

class ResponseHandler extends GlobalVariables
{
    private string $content;

    public function handler() : Response
    {
        if (!isset($response)) {
            $response = Container::getInstance()->make(Response::class);
            if ($response) {
                return $response;
            }
        }
        return false;
    }

    public function content(string $content) : void
    {
        $this->content = $content;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    public function setStatusCode(int $code) : self
    {
        http_response_code($code);
        return $this;
    }

    public function redirect(string $url) : self
    {
        header("Location: $url");
        header('HTTP/1.1 301 Moved Permanently');
        exit;
    }

    public function is_image(string $file)
    {
        if (@is_array(getimagesize($file))) {
            $image = true;
        } else {
            $image = false;
        }
    }

    /**
     * Transform Key -> transform source key from old to new key when present on $item
     * ==================================================================================.
     * @param array $source
     * @param array $item
     * @return array
     */
    public function transform_keys(array $source = [], array | null $newKeys = []) : array
    {
        $S = $source;
        if (isset($newKeys) && !empty($newKeys)) {
            foreach ($source as $key => $val) {
                if (isset($newKeys[$key])) {
                    $S = $this->_rename_arr_key($key, $newKeys[$key], $S);
                }
            }
        }
        return $S;
    }

    /**
     * Rename keys
     * ==================================================================================.
     * @param string $oldkey
     * @param string $newkey
     * @param array $arr
     * @return array|bool
     */
    private function _rename_arr_key(string $oldkey, string $newkey, array $arr = []) : bool|array
    {
        if (array_key_exists($oldkey, $arr)) {
            $arr[$newkey] = $arr[$oldkey];
            unset($arr[$oldkey]);
            return $arr;
        } else {
            return false;
        }
    }
    // public function cacheRefresh()
    // {
    //     $session = GlobalManager::get('global_session');
    //     if ($session->exists(BRAND_NUM)) {
    //         switch ($session->get(BRAND_NUM)) {
    //             case 2:
    //                 Cache::getcache()->init()->delete('phone_products_and_cart.txt');
    //                 break;

    //             default:
    //                 Cache::getcache()->init()->delete('clothes_products_and_cart.txt');
    //                 break;
    //         }
    //     }
    // }
}