<section class="userLR" id="Login-Register-System">
    <div class="log">
        <!--Login form-->
        <div class="modal fade" id="login-box" aria-hidden="true" aria-labelledby="login-boxLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close float-end" data-bs-dismiss="modal"
                            aria-label="Close"><span>&times;</span></button>
                        <?= $loginFrm?>
                    </div>
                    <div class="modal-footer">
                        <div class="input-group form-footer d-flex justify-content-center d-inline-block">
                            <p class="text-center"> <span class="d-inline-block pt-2" style="font-size:1rem">
                                    Nouveau? &nbsp;
                                    <a href="#" id="register-btn" class="close mt-1" data-bs-target="#register-box"
                                        data-bs-toggle="modal" data-bs-dismiss="modal">Enregistrer
                                        vous</a>
                                </span> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Register form-->
        <div class="modal fade" id="register-box" aria-hidden="true" aria-labelledby="regiter-boxLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close float-end" data-bs-dismiss="modal">
                            <span>&times;</span></button>
                        <?= $registerFrm?>
                    </div>
                    <div class="modal-footer">
                        <div class="form-footer d-flex justify-content-center mb-3">
                            <p class="text-center"><span class="d-inline-block pt-2">Vous avez déjà un compte?
                                    <a href="#" id="login-btn" class="close mt-1" data-bs-target="#login-box"
                                        data-bs-toggle="modal" data-bs-dismiss="modal">Connectez-vous</a></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Forgot password-->
        <div class="modal fade" id="forgot-box" tabindex="-1" aria-labelledby="forgot-boxLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close float-end" data-bs-dismiss="modal">
                            <span>&times;</span></button>
                        <?= $forgotFrm?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>