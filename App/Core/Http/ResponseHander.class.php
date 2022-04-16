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