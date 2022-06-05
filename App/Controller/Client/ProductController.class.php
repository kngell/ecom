<?php

declare(strict_types=1);
class ProductController extends Controller
{
    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return void
     */
    protected function singlePage(array $data = []) : void
    {
        /** @var ProductsManager */
        $products = $this->model(ProductsManager::class);
        $slug = array_pop($data);
        $cacheKey = Stringify::studlyCaps($slug);
        if (!$this->cache->exists($cacheKey)) {
            $this->cache->set($cacheKey, $products->getDetails($slug, 'slug', 'object')->get_results(), 20);
        }
        $this->render('product' . DS . 'product', $this->container(DisplayProductsInterface::class, [
            'product' => $this->cache->get($cacheKey),
            'products' => function ($products) {
                if (!$this->cache->exists('home_page')) {
                    return $this->cache->set('home_page', $products->getProducts());
                }
                return $this->cache->get('home_page');
            },
            'pm' => $products,
        ])->displaySingle());
    }
}