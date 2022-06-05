<?php

declare(strict_types=1);

abstract class AbstractPhonesProducts
{
    protected array|closure $products;
    protected ?ProductForm $productForm = null;
    protected ?ProceedToBuyForm $proceedToBuy = null;
    protected ?ProductsManager $pm = null;
    protected ?stdClass $product = null;
    protected ?AddToCartForm $addToCart = null;

    public function __construct(array|closure $products, ?ProductForm $productForm, ?ProceedToBuyForm $proceedToBuy = null, ?AddToCartForm $addToCart = null, ?ProductsManager $pm = null, ?stdClass $product = null)
    {
        $this->products = $products;
        $this->productForm = $productForm;
        $this->pm = $pm;
        $this->product = $product;
        $this->proceedToBuy = $proceedToBuy;
        $this->addToCart = $addToCart;
    }

    protected function getTemplate(string $path) : string
    {
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        return '';
    }

    protected function outputProduct(string $template, ?stdClass $product = null) : string
    {
        $template = str_replace('{{route}}', 'product' . DS . $product->slug, $template);
        $template = str_replace('{{image}}', $product->media != '' ? ImageManager::asset_img(unserialize($product->media)[0]) : ImageManager::asset_img('products/1.png'), $template);
        $template = str_replace('{{title}}', $product->title ?? 'Unknown', $template);
        $template = str_replace('{{price}}', (string) $this->pm->getMoney()->getAmount($product->regular_price), $template);
        $template = str_replace('{{ProductForm}}', $this->productForm->createForm('', $product), $template);
        $template = str_replace('{{brandClass}}', $product->categorie ?? 'Brand', $template);
        return $template;
    }

    protected function outputSingleProduct(string $template) : string
    {
        $p = $this->product;
        $template = str_replace('{{title}}', $p->title ?? 'Unknown', $template);
        $template = str_replace('{{brand}}', $p->item_brand ?? 'Brand', $template);
        $template = str_replace('{{image}}', isset($p->media) ? $p->media[0] : ImageManager::asset_img('products/product-540x60.jpg'), $template);
        if (isset($p->media) && count($p->media) > 0) {
            $galleryTemplate = $this->getTemplate(APP . 'Products' . DS . 'Phones' . DS . 'Templates' . DS . 'imageGalleryTemplate.php');
            $htmlGallery = '';
            for ($i = 0; $i < count($p->media); $i++) {
                $htmlItem = str_replace('{{imageGallery}}', isset($p->media[$i]) ? $p->media[$i] : ImageManager::asset_img('products/product-540x60.jpg'), $galleryTemplate);
                $htmlItem = str_replace('{{title}}', $p->title, $htmlItem);
                $htmlGallery .= $htmlItem;
            }
            $template = str_replace('{{imageGalleryTemplate}}', $htmlGallery, $template);
            $template = str_replace('{{proceedToBuyForm}}', $this->proceedToBuy->createForm(''), $template);
            $template = str_replace('{{addToCartForm}}', $this->addToCart->createForm('', $p), $template);
            $template = str_replace('{{comparePrice}}', (string) $this->pm->getMoney()->getAmount($p->compare_price), $template);
            $template = str_replace('{{regularPrice}}', (string) $this->pm->getMoney()->getAmount($p->regular_price), $template);
            $template = str_replace('{{savings}}', (string) $this->pm->getMoney()->getAmount($p->compare_price - $p->regular_price), $template);
            $template = str_replace('{{imageUser}}', ImageManager::asset_img('users/avatar.png'), $template);
            $template = str_replace('{{imageUser2}}', ImageManager::asset_img('users/default-female-avatar.jpg'), $template);
        }
        return $template;
    }
}