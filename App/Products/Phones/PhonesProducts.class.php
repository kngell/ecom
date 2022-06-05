<?php

declare(strict_types=1);

class PhonesProducts extends AbstractPhonesProducts implements DisplayProductsInterface
{
    private string $productPath;

    public function __construct(array|closure $products = [], ?ProductForm $productForm = null, ?ProceedToBuyForm $proceedToBuy = null, ?AddToCartForm $addToCart = null, ?ProductsManager $pm = null, ?stdClass $product = null)
    {
        $this->products = $products;
        $this->productPath = APP . 'Products' . DS . 'Phones' . DS . 'Templates' . DS . 'productsTemplate.php';
        if ($products instanceof Closure) {
            $products = $products->__invoke($pm);
        }
        parent::__construct($products, $productForm, $proceedToBuy, $addToCart, $pm, $product);
    }

    public function displayAll(): array
    {
        return [
            'topSales' => $this->displayTopSalesSetion(),
            'specialPrice' => $this->displaySpecialPriceSection(),
            'bannerAdds' => $this->displayBannerAddsSection(),
            'newProducts' => $this->displayNewProductsSection(),
            'bannerArea' => $this->displayBannerAreaSection(),
            'blogArea' => $this->displayBlogArea(),
        ];
    }

    public function displaySingle(): array
    {
        return [
            'singleProduct' => $this->singleProduct(),
            'topSales' => $this->displayTopSalesSetion(),
        ];
    }

    private function singleProduct() : string
    {
        if (count((array) $this->product) !== 0) {
            $spTemplate = $this->getTemplate(VIEW . 'client' . DS . 'brand' . DS . 'phones' . DS . 'product' . DS . 'partials' . DS . '_product_detailsTemplate.php');
            return $this->outputSingleProduct($spTemplate, $this->product);
        } else {
            return '<div class="text-center text-lead py-5">
            <h5>This product was not found</h5>
            </div>';
        }
    }

    private function displayBlogArea() : string
    {
        $blogTemplate = $this->getTemplate(VIEW . 'client' . DS . 'brand' . DS . 'phones' . DS . 'home' . DS . 'partials' . DS . '_blogTemplate.php');
        $blogTemplate = str_replace('{{blog1}}', ImageManager::asset_img('blog' . DS . 'blog1.jpg'), $blogTemplate);
        $blogTemplate = str_replace('{{blog2}}', ImageManager::asset_img('blog' . DS . 'blog2.jpg'), $blogTemplate);
        $blogTemplate = str_replace('{{blog3}}', ImageManager::asset_img('blog' . DS . 'blog3.jpg'), $blogTemplate);
        return $blogTemplate;
    }

    private function displayBannerAreaSection() : string
    {
        $bannerAreaTemplate = $this->getTemplate(VIEW . 'client' . DS . 'brand' . DS . 'phones' . DS . 'home' . DS . 'partials' . DS . '_banner_areaTemplate.php');
        $imgTemplate = $this->getTemplate(APP . 'Products' . DS . 'Phones' . DS . 'Templates' . DS . 'bannerTemplate.php');
        $html = '';
        if (isset($this->slider['image']) && is_array($this->slider['image'])) {
            foreach ($this->slider['image'] as $image) {
                $imgTemplate = str_replace('{{image}}', $image, $imgTemplate);
                $imgTemplate = str_replace('{{image}}', $image, $imgTemplate);
                $html .= $imgTemplate;
            }
        }
        return str_replace('{{bannerTemplate}}', $html, $bannerAreaTemplate);
    }

    private function displayNewProductsSection() : string
    {
        $newProductsTemplate = $this->getTemplate(VIEW . 'client' . DS . 'brand' . DS . 'phones' . DS . 'home' . DS . 'partials' . DS . '_new_productsTemplate.php');
        $productTemplate = $this->getTemplate(APP . 'Products' . DS . 'Phones' . DS . 'Templates' . DS . 'newProductsWrapperTemplate.php');
        $productTemplate = str_replace('{{singleProductTemplate}}', $this->getTemplate($this->productPath), $productTemplate);
        return $this->iteratedOutput($newProductsTemplate, $productTemplate);
    }

    private function displayBannerAddsSection() : string
    {
        $bannerAddTemplate = $this->getTemplate(VIEW . 'client' . DS . 'brand' . DS . 'phones' . DS . 'home' . DS . 'partials' . DS . '_banner_addsTemplate.php');
        $bannerAddTemplate = str_replace('{{banner1}}', ImageManager::asset_img('banner1-cr-500x150.jpg'), $bannerAddTemplate);
        $bannerAddTemplate = str_replace('{{banner2}}', ImageManager::asset_img('banner2-cr-500x150.jpg'), $bannerAddTemplate);

        return $bannerAddTemplate;
    }

    private function displayTopSalesSetion() : string
    {
        $topSalesTemplate = $this->getTemplate(VIEW . 'client' . DS . 'brand' . DS . 'phones' . DS . 'home' . DS . 'partials' . DS . '_top_salesTemplate.php');
        $productTemplate = $this->getTemplate(APP . 'Products' . DS . 'Phones' . DS . 'Templates' . DS . 'topSalesWrapperTemplate.php');
        $productTemplate = str_replace('{{singleProductTemplate}}', $this->getTemplate($this->productPath), $productTemplate);
        return $this->iteratedOutput($topSalesTemplate, $productTemplate);
    }

    private function displaySpecialPriceSection() : string
    {
        $brandButton = $this->specialPriceButton();
        $specialTemplate = $this->getTemplate(VIEW . 'client' . DS . 'brand' . DS . 'phones' . DS . 'home' . DS . 'partials' . DS . '_special_priceTemplate.php');
        $productTemplate = $this->getTemplate(APP . 'Products' . DS . 'Phones' . DS . 'Templates' . DS . 'specialPriceWrapperTemplate.php');
        $specialTemplate = str_replace('{{brandButton}}', !empty($brandButton) ? implode('', $brandButton) : '', $specialTemplate);
        $productTemplate = str_replace('{{singleProductTemplate}}', $this->getTemplate($this->productPath), $productTemplate);

        return $this->iteratedOutput($specialTemplate, $productTemplate);
    }

    private function specialPriceButton() :  array
    {
        $brandButton = [];
        if (isset($this->products) && $this->products != false) {
            $brands = array_unique(array_map(function ($prod) {
                return $prod->categorie;
            }, $this->products));
            sort($brands);
            if (isset($brands)) {
                $brandButton = array_map(function ($brand) {
                    return sprintf('<button class="btn" data-filter=".%s">%s</button>', $brand, $brand);
                }, $brands);
            }
        }
        return $brandButton;
    }

    private function iteratedOutput(string $template, string $productTemplate) : string
    {
        $html = '';
        if (is_array($this->products) && count($this->products) > 0) {
            shuffle($this->products);
            foreach ($this->products as $product) {
                $html .= $this->outputProduct($productTemplate, $product);
            }
        }
        return str_replace('{{productsTemplate}}', $html, $template);
    }
}