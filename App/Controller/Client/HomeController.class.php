<?php

declare(strict_types=1);
class HomeController extends Controller
{
    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return void
     */
    protected function indexPage(array $data = []) : void
    {
        /** @var ProductsManager */
        $products = $this->model(ProductsManager::class);
        $this->view()->addProperties(['pm' => $products, 'products' => $products->getProducts()]);
        $this->render('home' . DS . 'index');
    }
}