<section id="special-price">
    <div class="container w-75">
        <div class="title">
            <h4 class="font-rubik font-size-20">Special Price</h4>
            <div id="filters" class="button-group ms-auto float-end">
                {{brandButton}}
                <button class="btn is-checked" data-filter="*">All Brands</button>
            </div>
        </div>
        <hr class="divider mx-auto mt-0">
        <div class="grid">
            {{productsTemplate}}
        </div>
    </div>
</section>