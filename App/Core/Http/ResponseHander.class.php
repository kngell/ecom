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
        header('Location: ' . $url);
        return $this;
    }

    public function is_image(string $file)
    {
        if (@is_array(getimagesize($file))) {
            $image = true;
        } else {
            $image = false;
        }
    }

    public function cacheRefresh()
    {
        $session = GlobalManager::get('global_session');
        if ($session->exists(BRAND_NUM)) {
            switch ($session->get(BRAND_NUM)) {
                case 2:
                    Cache::getcache()->init()->delete('phone_products_and_cart.txt');
                    break;

                default:
                    Cache::getcache()->init()->delete('clothes_products_and_cart.txt');
                    break;
            }
        }
    }

    public function posts_frm_params()
    {
        return [
            'action' => '#',
            'method' => 'post',
            'formClass' => 'posts-frm needs-validation',
            'formCustomAttr' => 'novalidate',
            'formID' => 'posts-frm',
            'fieldWrapperClass' => 'input-box',
            'token' => Container::getInstance()->make(Token::class),
            'enctype' => 'multipart/form-data',
            'autocomplete' => 'nope',
            'alertErr' => true,
            'inputHidden' => [
                'postID' => ['id' => 'postID'],
                'postCommentCount' => ['id' => 'postCommentCount'],
                'userID' => ['id' => 'userID'],
                'updateAt' => ['id' => 'updateAt'],
                'deleted' => ['id' => 'deleted'],
                'operation' => ['id' => 'operation'],
            ],
            'fieldCommonclass' => [
                'fieldclass' => 'input-box__input',
                'labelClass' => 'input-box__label',
            ],
        ];
    }

    public function frm_params(string $frm_name, array $inpuHidden)
    {
        return [
            'action' => '#',
            'method' => 'post',
            'formClass' => $frm_name . ' needs-validation',
            'formCustomAttr' => 'novalidate',
            'formID' => $frm_name,
            'fieldWrapperClass' => 'input-box',
            'token' => Container::getInstance()->make(Token::class),
            'enctype' => 'multipart/form-data',
            'autocomplete' => 'nope',
            'alertErr' => true,
            'inputHidden' => $inpuHidden,
            'fieldCommonclass' => [
                'fieldclass' => 'input-box__input',
                'labelClass' => 'input-box__label',
            ],
        ];
    }
}