<div class="title">
    <h2>{{title}} </h2>
    <a href="#" class="btn btn-light d-none" id="back"><span class="icon back"></span>&nbsp;</i>&nbsp;Back
    </a>
    <summary class="modalite">&nbsp;{{nbj}}&nbsp;{{tarif}}&nbsp;{{lieu}}
    </summary>
</div>

<div class="container pt-1">
    <div class="row">
        <!-- Formulaire d'inscription -->
        <div class="form-box col-md-6" id="formation-wrapper">
            <form action="" class="needs-validation p-3" validate enctype="multipart/form-data" id="formation-frm"
                autocomplete="off">
                {{token}}
                <input type="hidden" name="formID" id="formID">
                <div id="alertErr"></div>
                <div class="form-group col">
                    <input type="text" class="form-control" name="nom" id="nom" autocomplete="off" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="nom" class="label-name"><span class="label-content">Nom :
                        </span></label>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="prenom" id="prenom" autocomplete="off"
                        placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="prenom" class="label-name"><span class="label-content">prénom :
                        </span></label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="societe" id="societe" autocomplete="off"
                        placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="societe" class="label-name"><span class="label-content">Société
                            :</span></label>
                </div>
                <div class="form-group ">
                    <input type="text" class="form-control" name="fonction" id="fonction" autocomplete="off"
                        placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="fonction" class="label-name"><span class="label-content">Fonction
                            :</span></label>
                </div>
                <div class="form-group ">
                    <input type="email" class="form-control" name="email" id="femail" autocomplete="off"
                        placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="email-is" class="label-name"><span class="label-content">Email
                            :</span></label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" id="phone" autocomplete="off" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="phone-is" class="label-name"><span class="label-content">Téléphone
                            :</span></label>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="message" id="message" autocomplete="off"
                        placeholder=" "></textarea>
                    <div class="invalid-feedback"></div>
                    <label for="message" class="label-name"><span class="label-content">Votre
                            message :</span></label>
                </div>
                <div class="form-group text-center">
                    <input type="submit" value="Envoyer" class="text-center btn button primary-button">
                </div>
            </form>
        </div>
        <div class="card col-md-6">
            <div class="card-body">
                <h5 class="card-title text-center py-4">Inscription à la formation : {{title}}.</h5>
                <p class="card-text text-center">Session du {{start_date}} au {{end_date}}.</p>
                <p class="card-text text-center">{{lieu}}</p>
                <p class="card-text text-center">Votre inscription vaut réservation.
                </p>
                <hr class="diviser">

                <p class="card-text text-center">La formation est dispensé dans nos locaux situés au 32, boulevard du
                    port - 95015
                    Cergy Pontoise.
                </p>
                <p class="card-text text-center">Vous recevrez dans 48h, les modalités pratiques d'accès et de
                    particpation à
                    la formation dans votre messagerie életronique.
                </p>

            </div>
        </div>

    </div>
</div>
<div id="session_id" style="display:none">{{session_id}}</div>