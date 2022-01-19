<section class="userLR" id="Login-Register-System">
    <div class="log">
        <!--Login form-->
        <div class="modal fade" id="login-box" aria-hidden="true" aria-labelledby="login-boxLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close float-end" data-bs-dismiss="modal"
                            aria-label="Close"><span>&times;</span></button>
                        <div class="form-wrapper rounded bg-light" id="">
                            <?=$this->userFrm->globalAttr(array_merge($this->userFrmAttr['global'], $this->userFrmAttr['login']))->begin(alertid:'loginAlert')?>
                            <p class="hint-text">Connectez-vous avec votre compte social média</p>
                            <div class="social-btn clearfix mb-3">
                                <a href="javascript:void(0)" class="btn btn-primary  float-start" id="fblink"><i
                                        class="fab fa-facebook"></i>
                                    Facebook</a>
                                <a href="#" class="btn btn-info float-end"><i class="fab fa-twitter"></i>
                                    Twitter</a>
                            </div>
                            <div class="or-seperator"><b>ou</b></div>
                            <!--Log-->
                            <?= $this->userFrm->input('email')->label('E-mail:')?>
                            <?= $this->userFrm->input('password')->label('Password:')->passwordType()?>
                            <div class="row g-3">
                                <?= $this->userFrm->checkbox('rem')->Label('Se souvenir')->class('checkbox__input')->spanClass('checkbox__box')->LabelClass('checkbox')->setFieldWrapperClass('col')->id('customCheckRem')?>
                                <div class="col">
                                    <a href="#" id="forgot-btn" class="float-end" class="close" data-bs-dismiss="modal"
                                        data-bs-toggle="modal" data-bs-target="#forgot-box">Mot
                                        de
                                        passe oublié</a>
                                </div>
                            </div>
                            <?= $this->userFrm->button()->id('l_btn')->text('Login')->class('button')->submit()?>
                            <?=$this->userFrm->end()?>
                        </div>
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
                        <div class="form-wrapper rounded bg-light" id="">
                            <div class="upload-profile-image d-flex justify-content-center pb-1">
                                <div class="text-center">
                                    <div class="d-flex justify-content-center"> <img class="camera-icon"
                                            src="<?=IMG?>camera/camera-solid.svg" alt="camera" />
                                    </div>
                                    <img src="<?=IMG?>users/avatar.png" class="img rounded-circle" alt="profile" />
                                    <small class="form-text">Profile</small>
                                    <input type="file" form="register-frm" class="form-control upload-profile"
                                        name="profileUpload" id="upload-profile">
                                </div>
                            </div>
                            <hr class="mb-3">
                            <?=$this->userFrm->globalAttr(array_merge($this->userFrmAttr['global'], $this->userFrmAttr['register']))->begin(alertid:'regAlert')?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <?= $this->userFrm->input('lastName')->label('Prénom:')?>
                                </div>
                                <div class="col-md-6">
                                    <?= $this->userFrm->input('firstName')->label('Nom:')?>
                                </div>
                            </div>
                            <?= $this->userFrm->input('userName')->label('Surnom:')?>
                            <?= $this->userFrm->input('email')->label('E-Mail:')->emailType()->id('reg_email')?>
                            <?= $this->userFrm->input('password')->label('Mot de passe:')->passwordType()->id('pass')?>
                            <?= $this->userFrm->input('cpassword')->label('Confirmer:')->passwordType()->id('cpass')?>
                            <?= $this->userFrm->checkbox('terms')->Label('<div>J\'accepte&nbsp;<a href="#">les termes&nbsp;</a>&amp;&nbsp;<a href="#">conditions</a> d\'utilisation</div>')->class('checkbox__input')->spanClass('checkbox__box')->LabelClass('checkbox')?>
                            <?= $this->userFrm->button()->id('reg_btn')->text('Register')->class('button')->submit()?>
                            <?=$this->userFrm->end()?>

                        </div>
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
                        <div class="form-wrapper rounded bg-light" id="">
                            <form action="" method="post" role="form" class="p-2" id="forgot-frm" autocomplete="off">
                                <?=FH::csrfInput('csrftoken', $this->token->generate_token(8, 'forgot-frm')); ?>
                                <div id="forgotAlert"></div>
                                <div class="input-group mb-3"> <small class="text-muted text-center">To reset your
                                        password,
                                        enter your
                                        email</small> </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control form-control-lg" name="email"
                                        id="forgot_email" placeholder="E-Mail" autocomplete="false">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="submit" name="forgot" class="btn btn-primary btn-block" id="forgot-btn"
                                        value="Reset password">
                                </div>
                                <div class="input-group form-footer d-flex justify-content-center mb-3"> <a href="#"
                                        id="back-btn" class="close" data-bs-dismiss="modal" data-bs-toggle="modal"
                                        data-bs-target="#login-box">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>