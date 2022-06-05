<section id="top-sale">
    <div class="container py-5 w-75">
        <h4 class="font-rubik font-size-20">Top Sales</h4>
        <hr class="divider mx-auto mt-0">
        <!-- Owl carousel -->
        <?php $products = $this->getProperty('products'); if (($products != null) && count($products)) : shuffle($products); ?>
        <div class="owl-carousel owl-theme">
            <?php foreach ($products as $product) : ?>
            <div class="item py-2">
                <div class="product font-rale ">
                    <a href="<?= 'product' . DS?><?= $product->slug ?>">
                        <div style="overflow:hidden;"><img
                                src="<?= $product->media != '' ? ImageManager::asset_img(unserialize($product->media)[0]) : ImageManager::asset_img('products/1.png') ?>"
                                alt="<?= $product->title ?? 'Unknown' ?>" class="img-fluid">
                        </div>
                    </a>
                    <div class="text-center">
                        <h6><?= $product->title ?? 'Unknown' ?>
                        </h6>
                        <div class="rating text-warning font-size-12">
                            <span><i class="fas fa fa-star"></i></span>
                            <span><i class="fas fa fa-star"></i></span>
                            <span><i class="fas fa fa-star"></i></span>
                            <span><i class="fas fa fa-star"></i></span>
                            <span><i class="far fa-star"></i></span>
                        </div>
                        <div class="price py-2">
                            <span
                                class="product_regular_price"><?= $this->getProperty('pm')->getMoney()->getAmount($product->regular_price) ?? 0 ?></span>
                        </div>
                        <form class="add_to_cart_frm">
                            <input type="hidden" name="item_id" value="<?= $product->pdtID ?? 1 ?>">
                            <input type="hidden" name="user_id" value="1">
                            <?=$this->token->csrfInput('csrftoken', 'add_to_cart_frm' . $product->pdtID ?? 1); ?>
                            <?php
                                if (isset($this->user_cart[0])) {
                                    if (in_array($product->pdtID, array_map(function ($item) {
                                        if ($item->c_content == 'cart') {
                                            return $item->item_id;
                                        }
                                    }, $this->user_cart[0]))) {
                                        echo ' <button type="submit" class="btn btn-success font-size-12">In the cart</button>';
                                    } elseif (in_array($product->pdtID, array_map(function ($item) {
                                        if ($item->c_content == 'wishlist') {
                                            return $item->item_id;
                                        }
                                    }, $this->user_cart[0]))) {
                                        echo ' <button type="submit" class="btn btn-info font-size-12">In wishlist</button>';
                                    } else {
                                        echo '<button type="submit" class="btn btn-warning font-size-12">Add to
                                    Cart</button>';
                                    }
                                } else {
                                    echo '<button type="submit" class="btn btn-warning font-size-12">Add to
                                    Cart</button>';
                                } ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <!-- End Owl Carousel -->
        </div>
        <?php endif; ?>
</section>