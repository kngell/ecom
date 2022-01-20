<form action="" novalidate>
    <div class="row g-3">
        <div class="col-sm-6">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="John">
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-sm-6">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Smith">
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-12">
            <label for="userName" class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" id="userName" name="userName" placeholder="jsmith">
            </div>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-5">
            <label for="country" class="form-label">Country</label>
            <select class="form-control" id="country" name="country">
                <option value="">Choose...</option>
                <option value="Australia">Autralia</option>
                <option value="Australia">USA</option>
                <option value="Australia">China</option>
            </select>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-4">
            <label for="region" class="form-label">Region</label>
            <select class="form-control" id="region" name="region">
                <option value="">Choose...</option>
                <option value="Australia">IDF</option>
                <option value="Australia">Vaucluse</option>
                <option value="Australia">Val d4oise</option>
            </select>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-3">
            <label for="postcode" class="form-label">Code postal</label>
            <input type="number" class="form-control" id="postcode" name="postcode" placeholder="95000">
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <hr class="my-4">
    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="sameAddress" name="sameAddress">
            <label for="sameAddress" class="form-check-label">Shipping address
                is the
                same
                as billing.</label>
            <div class="invalid-feedback"></div>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="saveInfos" name="sameAddress">
            <label for="saveInfos" class="form-check-label">Save these
                infos.</label>
            <div class="invalid-feedback"></div>
        </div>

        <hr class="my-4">
        <h5 class="mb-3">Payments infos</h5>
        <div class="form-check">
            <input type="radio" class="form-check-input" id="credit_card" name="payment_method" checked>
            <label for="credit_card">Credit Card</label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" id="direct_debit" name="payment_method">
            <label for="direct_debit">Direct Debit</label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" id="paypal" name="payment_method">
            <label for="paypal">PayPal</label>
        </div>

        <div class="row my-3 gy-3">
            <div class="col-md-6">
                <label for="fullName" class="form-label">Name on card</label>
                <input type="text" class="form-control" id="fullName" name="fullName">
                <small class="text-muted">Full Name as displayed on card</small>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-6">
                <label for="cc_number" class="form-label">Credit Card
                    Number</label>
                <input type="text" class="form-control" id="cc_number" name="cc_number">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-3">
                <label for="cc_expire" class="form-label">Expiration</label>
                <input type="text" class="form-control" id="cc_expire" name="cc_expire">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-3">
                <label for="cc_cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cc_cvv" name="cc_cvv">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <hr class="my-4">
        <button class="btn btn-primary btn-block">Continue to check out</button>
    </div>
</form>