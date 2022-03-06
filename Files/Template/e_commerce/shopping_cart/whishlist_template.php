<!-- Cart item -->
<div class="row cart-row">
    <div class="col-sm-2 cart-row__img">
        <img src="{{image}}" alt="cart1" class="img-fluid" style="height:120px;">
    </div>
    <div class="col-sm-8 cart-row__details">
        <h5 class="title">{{title}}
        </h5>
        <small>By {{brand}}</small>
        <!-- Rating section -->
        <div class="rating">
            <div class="rating__star text-warning font-size-12">
                <span><i class="fas fa fa-star"></i></span>
                <span><i class="fas fa fa-star"></i></span>
                <span><i class="fas fa fa-star"></i></span>
                <span><i class="fas fa fa-star"></i></span>
                <span><i class="far fa-star"></i></span>
            </div>
            <a href="#" class="px-2 rating__text">20,534 ratings</a>
        </div>
        <!-- !Rating section -->
        <!-- Produt quantity -->
        <div class="cart-qty">
            <form action="#" class="delete-cart-item-frm{{product_id}}">
                {{del_save_token}}
                <input type="hidden" name="item_id" value="{{product_id}}">
                <input type="hidden" name="user_id" value="1">
                <button type="submit" class="btn font-baloo pl-0 pr-3 border-right deleteBtn">Supprimer</button>
                <button type="button" class="button save-add">Add to cart</button>
            </form>
        </div>
        <!-- !Produt quantity -->
    </div>
    <div class="col-sm-2 text-right cart-row__price">
        <div class="price_wrapper">
            <span class="product_price">{{price}}</span>
        </div>
    </div>
</div>
<!-- !Cart item -->