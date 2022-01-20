<div class="modal fade" id="modal_success_checkout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_success_checkoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_success_checkoutLabel">Murchase Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="card-body">
                            <h2>Thank you for purchazing.</h2>
                            <hr>
                            <p>Your transaction ID is : {{transactionID}}
                            </p>
                            <p>Please check your Email for more informations</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{link1}}" class="btn btn-warning">Buy again</a>
                            <a href="{{link2}}" class="btn btn-success">Continue
                                shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>