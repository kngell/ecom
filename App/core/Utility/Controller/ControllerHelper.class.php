<?php

declare(strict_types=1);

final class ControllerHelper
{
    protected Container $container;

    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->token = $this->container->make(Token::class);
    }

    public function getSliders()
    {
        $cache = Cache::getcache()->init(CACHE_DIR, 75);
        if (!$std = $cache->read('slider.txt')) {
            $std = new stdClass();
            $sliders = $this->container->make(SlidersManager::class)->getAllItem(['return_mode' => 'class']);
            if ($sliders->count() > 0) {
                $sliders = $sliders->get_results();
                foreach ($sliders as $slider) {
                    $page = $slider->page_slider;
                    $image = $slider->p_media !== null ? unserialize($slider->p_media) : ['products' . US . 'product-80x80.jpg'];
                    foreach ($image as $key => $url) {
                        $image[$key] = ImageManager::asset_img($url);
                    }
                    $std->$page['title'] = $slider->slider_title;
                    $std->$page['text'] = $slider->slider_text;
                    $std->$page['image'] = $image;
                    $std->$page['btn_text'] = $slider->slider_btn_text;
                }
                $cache->write('slider.txt', $std);

                return $std;
            }
        }

        return $std;
    }

    public function getSettings()
    {
        $cache = Cache::getcache()->init(CACHE_DIR, 75);
        if (!$settingResult = $cache->read('settings.txt')) {
            $settings = $this->container->make(GeneralSettingsManager::class)->getAllItem(['return_mode' => 'class']);
            $settingResult = new stdClass();
            if ($settings->count() > 0) {
                foreach ($settings->get_results() as $setting) {
                    $settingResult->{$setting->setting_key} = $setting->value ?? '';
                }
            }
            $cache->write('settings.txt', $settingResult);

            return $settingResult;
        }

        return $settingResult;
    }

    public function get_product_and_cart(int $brand) : mixed
    {
        $cache = Cache::getcache()->init(CACHE_DIR, 75);
        switch ($brand) {
            case '3':
                if (!$clothes = $cache->read('clothes_products_and_cart.txt')) {
                    $clothes['products'] = $this->container->make(ProductsManager::class)->get_Products($brand);
                    $clothes['cart'] = $this->container->make(CartManager::class)->get_userCart() ?? [];
                    $cache->write('clothes_products_and_cart.txt', $clothes);
                }
                break;

            default:
            if (!$phones = $cache->read('phone_products_and_cart.txt')) {
                $phones['products'] = $this->container->make(ProductsManager::class)->get_Products($brand);
                $phones['cart'] = $this->container->make(CartManager::class)->get_userCart() ?? [];
                $cache->write('phone_products_and_cart.txt', $phones);
            }
                break;
        }
        $data['products'] = array_merge($clothes['products'] ?? [], $phones['products'] ?? []);
        $data['cart'] = array_merge($clothes['cart'] ?? [], $phones['cart'] ?? []);
        return $data;
    }

    public function form_params()
    {
        return [
            'global' => [
                'action' => '#',
                'method' => 'post',
                'formClass' => 'needs-validation',
                'formCustomAttr' => 'novalidate',
                'fieldWrapperClass' => 'input-box',
                'token' => $this->token,
                'enctype' => 'multipart/form-data',
                'autocomplete' => 'nope',
                'alertErr' => true,
                'fieldCommonclass' => [
                    'fieldclass' => 'input-box__input',
                    'labelClass' => 'input-box__label',
                ],

            ],
            'login' => [
                'inputHidden' => [
                    'checkout' => [
                        'id' => 'input_checkout',
                    ],
                ],
                'formID' => 'login-frm',
            ],
            'register' => [
                'formID' => 'register-frm',
            ],
        ];
    }

    private function frm_params(View $view)
    {
        return [
            'action' => '#',
            'method' => 'post',
            'formClass' => 'user-ckeckout-frm needs-validation',
            'formCustomAttr' => 'novalidate',
            'formID' => 'user-ckeckout-frm',
            'fieldWrapperClass' => 'input-box',
            'token' => $this->token,
            'model' => $view->user_data,
            'enctype' => 'multipart/form-data',
            'autocomplete' => 'nope',
            'alertErr' => true,
            'inputHidden' => [
                'total-ttc' => isset($view->user_cart[2][1]) ? $view->user_cart[2][1] : '',
                'total-ht' => isset($view->user_cart[2][0]) ? $view->user_cart[2][0] : '',
            ],
            'nestField' => true,
            'fieldCommonclass' => [
                'fieldclass' => 'input-box__input',
                'labelClass' => 'input-box__label',
            ],
    ];
    }
}
