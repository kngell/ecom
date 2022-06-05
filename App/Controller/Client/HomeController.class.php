<?php

declare(strict_types=1);
class HomeController extends Controller
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return void
     */
    protected function indexPage(array $data = []) : void
    {
        $this->render('home' . DS . 'index', $this->container(DisplayProductsInterface::class, [
            'products' => function ($products) {
                if (!$this->cache->exists('home_page')) {
                    return $this->cache->set('home_page', $products->getProducts());
                }
                return $this->cache->get('home_page');
            },
        ])->displayAll());
    }
}