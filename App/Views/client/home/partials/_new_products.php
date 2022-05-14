<section id="new-phones">
    <div class="container w-75">
        <div class="row title">
            <h4 class="font-rubik font-size-20">New Phones</h4>
        </div>
        <hr class="divider mx-auto mt-0">
        <!-- Owl carousel -->
        <?php $products = $this->getProperty('products'); $products != null ? shuffle($products) : ''?>
        <div class="owl-carousel owl-theme">
            <?php if (isset($products)) {
    foreach ($products as $product) :?>
            <div class="item py-2 bg-light">
                <div class="product font-rale ">
                    <a href="<?=PROOT?>product/<?=$product->p_slug?>"><img
                            src="<?= $product->p_media != '' ? ImageManager::asset_img(unserialize($product->p_media)[0]) : ImageManager::asset_img('products/1.png') ?>"
                            alt="<?=$product->p_title ?? 'Unknown'?>" class="img-fluid"></a>
                    <div class="text-center">
                        <h6><?=$product->p_title ?? 'Unknown'?>
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
                                class="product_regular_price"><?=$this->getProperty('pm')->getMoney()->getAmount($product->p_regular_price) ?? 0?></span>
                        </div>
                        <form class="add_to_cart_frm">
                            <input type="hidden" name="item_id" value="<?=$product->pdtID ?? 1 ?>">
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
            <?php endforeach;
}?>
        </div>
        <!-- End Owl Carousel -->
    </div>
</section>