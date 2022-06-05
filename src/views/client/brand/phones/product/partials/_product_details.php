<section id="product" class="py-3">
    <div class="container w-75">
        <div class="row product-details">
            <?php $p = $this->getProperty('p_details'); ?>
            <div class="col-sm-6 pt-2">
                <div class="product-image-box text-center pt-3 px-3rounded">
                    <img src="<?= isset($p->media) ? $p->media[0] : ImageManager::asset_img('products/product-540x60.jpg') ?>"
                        alt="Product" class="img-fluid">
                </div>
                <!-- begin product-gallery -->
                <?php if (isset($p->media) && count($p->media) > 1):?>
                <div class="product-gallery d-flex justify-content-center mb-1 mx-auto owl-carousel owl-theme">
                    <?php for ($i = 1; $i < count($p->media); $i++) {?>
                    <div class="product-gallery-item">
                        <div class="product-img">
                            <a href="javascript: void(0);" class="">
                                <img src="<?=isset($p->media[$i]) ? $p->media[$i] : ImageManager::asset_img('products/product-540x60.jpg')?>"
                                    class="img-fluid img-thumbnail p-2" style="max-width: 60px;" alt="Product-img">
                            </a>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <?php endif; ?>

                <!-- end product-gallery -->
                <div class="row pt-3 font-size-16 font-baloo mb-3">
                    <div class="col">
                        <form action="">
                            <button type="submit" class="btn btn-danger font-size-14 form-control">Proceed to
                                buy</button>
                        </form>
                    </div>
                    <div class="col">
                        <form class="add_to_cart_frm">
                            <input type="hidden" name="item_id" value="<?= $p->pdtID ?? 1 ?>">
                            <input type="hidden" name="user_id" value="1">
                            <?php $pdtID = $p->pdtID ?? 1?>
                            <?= $this->token->csrfInput('csrftoken', 'add_to_cart_frm'); ?>
                            <?php
                            if (isset($p->pdtID) && isset($this->user_cart) && in_array($p->pdtID, $this->user_cart)) {
                                echo ' <button type="submit" class="btn btn-success font-size-14 form-control">In the cart</button>';
                            } else {
                                echo '<button type="submit" class="btn btn-warning font-size-14 form-control">Add to
                                Cart</button>';
                            }
                            ?>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-sm-6 pt-5 pb-4">
                <h5 class="font-baloo font-size-20"><?= $p->title ?? 'Unknown' ?>
                </h5>
                <small>By <?= $p->item_brand ?? 'Brand' ?></small>
                <div class="d-flex">
                    <div class="rating text-warning font-size-12">
                        <span><i class="fas fa fa-star"></i></span>
                        <span><i class="fas fa fa-star"></i></span>
                        <span><i class="fas fa fa-star"></i></span>
                        <span><i class="fas fa fa-star"></i></span>
                        <span><i class="far fa-star"></i></span>
                    </div>
                    <a href="#" class="px-2 font-rale font-size-14 text-decoration-none">20534 Ratings | 1000+ answered
                        questions</a>
                </div>
                <hr class="m-0">
                <!-- Price section  -->
                <table class="my-3">
                    <tr class="font-rale font-size-14">
                        <td>M.R.P : </td>
                        <td><strike><?=$this->getProperty('pm')->getMoney()->getAmount($p->compare_price)?>
                            </strike></td>
                    </tr>
                    <tr class="font-rale font-size-14">
                        <td>Deal Price :</td>
                        <td class="font-size-20 text-danger">
                            $<span><?= $this->getProperty('pm')->getMoney()->getAmount($p->regular_price) ?? 0 ?></span><small
                                class="text-dark font-size-12">&nbsp;&nbsp;inclusive of
                                all
                                taxes</small>
                        </td>
                    </tr>
                    <?php $save = $p->compare_price - $p->regular_price?>
                    <tr class="font-rale font-size-14">
                        <td>You Save : </td>
                        <td><span
                                class="font-size-16 text-danger"><?=$this->getProperty('pm')->getMoney()->getAmount($save)?></span>
                        </td>
                    </tr>
                </table>
                <!-- End Price section -->

                <!-- policy -->
                <div id="policy">
                    <div class="d-flex">
                        <div class="return text-center me-5">
                            <div class="font-size-20 my-2 color-second">
                                <span><i class="fas fas fa-retweet border p-3 rounded-pill"></i></span>
                            </div>
                            <a href="#" class="font-rale font-size-12 text-decoration-none">10 Days<br> Replacement</a>
                        </div>
                        <div class="return text-center me-5">
                            <div class="font-size-20 my-2 color-second">
                                <span><i class="fas fas fa-truck border p-3 rounded-pill"></i></span>
                            </div>
                            <a href="#" class="font-rale font-size-12 text-decoration-none">K'nGELL<br> Delivered</a>
                        </div>
                        <div class="return text-center me-5">
                            <div class="font-size-20 my-2 color-second">
                                <span><i class="fas fas fa-check-double border p-3 rounded-pill"></i></span>
                            </div>
                            <a href="#" class="font-rale font-size-12 text-decoration-none">1 year<br> warranty</a>
                        </div>
                    </div>
                </div>
                <!-- End policy -->
                <hr>
                <!-- Order details -->
                <div id="order-details" class="font-rale d-flex flex-column text-dark">
                    <small>Delivery by : Mar 29 - Apr 1</small>
                    <small>Sold by <a href="#" class="text-decoration-none">K'nGELL electronics&nbsp;</a>(4.5 out of 5 |
                        19,198
                        ratings)</small>
                    <small><i class="fas fa-map-marker-alt color-primary"></i>&nbsp;&nbsp;Deliver to Customer -
                        424201</small>
                </div>
                <!-- !Order details -->
                <div class="row">
                    <div class="col-6">
                        <!-- colors -->
                        <div class="color my-3">
                            <div class="d-flex justify-content-between">
                                <h6 class="font-baloo">Color:</h6>
                                <div class="p-2 color-yellow-bg rounded-circle"><button
                                        class="btn font-size-14"></button></div>
                                <div class="p-2 color-primary-bg rounded-circle"><button
                                        class="btn font-size-14"></button></div>
                                <div class="p-2 color-second-bg rounded-circle"><button
                                        class="btn font-size-14"></button></div>
                            </div>
                        </div>
                        <!-- !Colors -->
                    </div>
                    <div class="col-6">
                        <!-- Product qty section -->
                        <div class="qty d-flex" id="qty">
                            <h6 class="font-baloo">Qty:</h6>
                            <div class="px-4 d-flex font-rale">
                                <button class="qty-up border bg-light"><i class="fas fa-angle-up"></i></button>
                                <input type="text" class="qty_input border px-2 w-50 bg-light" desabled value="1"
                                    placeholder="1">
                                <button class="qty-down border bg-light"><i class="fas fa-angle-down"></i></button>
                            </div>
                        </div>
                        <!-- !Product qty section -->
                    </div>
                </div>
                <!-- Mobile size  -->
                <div class="size my-2">
                    <h6 class="font-baloo">Size :</h6>
                    <div class="d-flex justify-content-between w-75">
                        <div class="font-rubik border p-2">
                            <button class="btn p-0 font-size-14">4GB RAM</button>
                        </div>
                        <div class="font-rubik border p-2">
                            <button class="btn p-0 font-size-14">6GB RAM</button>
                        </div>
                        <div class="font-rubik border p-2">
                            <button class="btn p-0 font-size-14">8GB RAM</button>
                        </div>
                    </div>
                </div>
                <!-- !Mobile size -->
            </div>
        </div>
        <div class="row" id="product-description">
            <div class="col-12">
                <div class="tab-style3">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="Description-tab" data-bs-toggle="tab" href="#Description" role="tab"
                                aria-controls="Description" aria-selected="false">Description</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="Additional-info-tab" data-bs-toggle="tab" href="#Additional-info"
                                role="tab" aria-controls="Additional-info" aria-selected="false">Additional info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews" role="tab"
                                aria-controls="Reviews" aria-selected="true">Reviews (2)</a>
                        </li>
                    </ul>
                    <div class="tab-content shop-info-tab">
                        <div class="tab-pane fade font-rubik" id="Description" role="tabpanel"
                            aria-labelledby="Description-tab">
                            <p class="font-rale font-size-14">Lorem ipsum dolor sit amet, consectetur adipiscing
                                elit. Maecenas quis enim volutpat,
                                interdum odio
                                vel, sagittis massa. Phasellus id turpis leo. Aenean pretium non massa a ultricies.
                                Integer quis
                                nulla quis nulla mollis vestibulum. Nullam erat nulla, elementum placerat augue in,
                                ultrices aliquet
                                tellus. Mauris suscipit orci at mauris consequat, vel posuere nibh vestibulum.
                                Vestibulum sit amet
                                turpis dolor. Praesent commodo nibh sed mi facilisis, vitae auctor felis semper.
                                Suspendisse
                                potenti. Aenean tempor, quam sit amet hendrerit euismod, enim tellus aliquam dolor,
                                quis tristique
                                tortor ex eu leo. Duis ligula eros, maximus non mattis sed, vehicula feugiat urna.
                                Ut faucibus
                                hendrerit eleifend. Aenean ipsum velit, mollis sit amet felis ut, interdum luctus
                                nibh.</p>
                            <p class="font-rale font-size-14">Nam placerat justo vitae porttitor euismod. Nullam sit
                                amet enim id elit varius
                                vestibulum ac ac
                                tortor. Nunc non tortor vitae nulla laoreet finibus. Curabitur at sagittis neque.
                                Sed rutrum ante
                                sem. Praesent mattis justo quis congue convallis. In at molestie odio, a porttitor
                                ipsum. Duis
                                elementum, augue vitae semper mollis, nunc ex vulputate diam, et luctus diam urna ut
                                sapien. Lorem
                                ipsum dolor sit amet, consectetur adipiscing elit. Maecenas pharetra tortor eget sem
                                vehicula, a
                                mollis dui molestie. Suspendisse sagittis lectus in velit tristique, vel luctus odio
                                ornare. Ut dui
                                lorem, hendrerit sed aliquet non, eleifend eu ex. Mauris vel purus vulputate,
                                consequat velit non,
                                tempus enim. Mauris nibh nunc, consequat vulputate massa non, vehicula imperdiet
                                leo. Quisque
                                gravida justo a enim blandit, in egestas velit varius.</p>
                        </div>
                        <div class="tab-pane fade" id="Additional-info" role="tabpanel"
                            aria-labelledby="Additional-info-tab">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Capacity</td>
                                        <td>0.2 Kg</td>
                                    </tr>
                                    <tr>
                                        <td>Color</td>
                                        <td>Black, Brown, Red,</td>
                                    </tr>
                                    <tr>
                                        <td>Water Resistant</td>
                                        <td>Yes</td>
                                    </tr>
                                    <tr>
                                        <td>Material</td>
                                        <td>Heathered, poly/cotton/rayon blend</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade active show" id="Reviews" role="tabpanel"
                            aria-labelledby="Reviews-tab">
                            <div class="comments">
                                <h4 class="product-tab-title">2 Review For <span>T-Shirt Form Girls</span></h4>
                                <ul class="list-none comment-list mt-4">
                                    <li class="d-flex flex-row">
                                        <div class="comment-img me-3">
                                            <img src="<?=ImageManager::asset_img('users/avatar.png')?>"
                                                style="height:60px" alt="User" class="rounded">
                                        </div>
                                        <div class="comment-block">
                                            <div class="rating-wrap float-end">
                                                <div class="rating">
                                                    <div class="product-rate" style="width:80%"></div>
                                                </div>
                                            </div>
                                            <div class="customer-meta">
                                                <span class="review-author fw-700 d-block">Schirsten Vander</span>
                                                <span class="comment-date"><i>March 5, 2020</i></span>
                                            </div>
                                            <div class="description">
                                                <p class="font-rale font-size-14">Duis interdum gravida erat eu
                                                    congue. Aenean malesuada sapien
                                                    risus, sed lacinia dui
                                                    sodales venenatis. Sed vitae felis vitae dolor consequat semper.
                                                    Vestibulum hendrerit
                                                    nulla justo, et hendrerit nibh accumsan et. Sed faucibus erat id
                                                    eleifend accumsan.</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex flex-row">
                                        <div class="comment-img me-3">
                                            <img src="<?=ImageManager::asset_img('users/default-female-avatar.jpg')?>"
                                                style="height:60px" alt="" class="rounded">
                                        </div>
                                        <div class="comment-block">
                                            <div class="rating-wrap float-end">
                                                <div class="rating">
                                                    <div class="product-rate" style="width:60%"></div>
                                                </div>
                                            </div>
                                            <div class="customer-meta">
                                                <span class="review-author fw-700 d-block">Robert Nordic</span>
                                                <span class="comment-date"><i>Apr 5, 2020</i></span>
                                            </div>
                                            <div class="description">
                                                <p class="font-rale font-size-14">Praesent tristique ex sit amet
                                                    ante sodales, vel dignissim nisl
                                                    laoreet. Nunc sit amet
                                                    orci dolor. Duis sit amet porttitor diam. Sed nisi sapien,
                                                    lobortis eget lacus vitae,
                                                    tincidunt dapibus mauris.</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>

    </div>
</section>