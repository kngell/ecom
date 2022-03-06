<div class="infos-personnelles">
    <div class="tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <!-- <span class="icon overview"><i class="far fa-eye"></i></span> -->
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                    type="button" role="tab" aria-controls="profile" aria-selected="true"> <span class="icon"><i
                            class="fal fa-eye"></i></span><span>Apperçu</span></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile"
                    type="button" role="tab" aria-controls="edit-profile" aria-selected="false"><span class="icon"><i
                            class="fal fa-edit"></i></span><span>Modifier</span> </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button"
                    role="tab" aria-controls="settings" aria-selected="false"><i class="fal fa-key icon"></i><span>Mot
                        de
                        passe</span></button>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row g-0">
                <div class="profile">
                    <div class="profile-body">
                        <div class="profile-title">
                            <h5 class="title">Infos Personnelles</h5>
                        </div>
                        <div class="profile-details">
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Nom:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>{{nom}}</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Profession:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>{{profession}}</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Email:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>{{email}}</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Téléphone:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>{{phone}}</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Address:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>{{address}}</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Genre:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>{{gender}}</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Birthday:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>{{dob}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-footer mt-45">
                        <div class="profile-title">

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
            <div class="row g-0">
                <div class="user-form-box">
                    <form action="#" method="post" class="needs-validation" novalidate enctype="multipart/form-data"
                        id="user-profile-frm">
                        {{csrftoken}}
                        <input type="hidden" name="userID" id="userID" value="{{userID}}">
                        <input type="hidden" name="date_enreg" id="registerDate" value="{{registerDate}}">
                        <input type="hidden" name="updateAt" id="updateAt" value="{{updateAt}}">
                        <input type="hidden" name="deleted" id="deleted" value="{{deleted}}">
                        <input type="hidden" name="usdID" id="usdID" value="{{usdID}}">
                        <div id="alertErr"></div>
                        <h5 class="">Profile</h5>
                        <hr class="mb-3 mt-2">
                        <div class="row g-3">
                            <div class="col-md-10">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="input-box">
                                            <label for="firstName" class="form-label">Prénom: <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" type="text" id="firstName" name="firstName"
                                                placeholder="John" value="{{firstName}}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-box">
                                            <label for="lastName" class="form-label">Nom: <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" type="text" id="lastName" name="lastName"
                                                placeholder="Smith" value="{{lastName}}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-box">
                                            <label for="phone" class="form-label">Téléphone:</label>
                                            <input class="form-control" type="text" id="phone" name="phone"
                                                value="{{phone}}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-box">
                                            <label for="email" class="form-label">Courriel: <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" type="email" id="email" name="email"
                                                value="{{email}}">
                                            <div class=" invalid-feedback">
                                            </div>
                                        </div>
                                    </div>

                                </div> <!-- end row -->
                            </div>
                            <div class="col-md-2">
                                <div class="upload-box">
                                    <div class="upload-image-wrapper d-flex justify-content-center">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <img class="camera-icon"
                                                    src="/kngell/public/assets/img/camera/camera-solid.svg"
                                                    alt="camera" />
                                            </div>
                                            <img src="{{profileImage}}" class="img" alt="profile" />
                                            <small class="form-text text-black-50">Choose Profile</small>
                                            <label for="upload-profile"></label>
                                            <input type="file" class="form-control upload-profile" name="profileUpload"
                                                id="upload-profile">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <div class="input-box">
                                    <label for="u_function" class="form-label">Profession:</label>
                                    <input class="form-control" type="text" id="u_function" name="u_function"
                                        value="{{profession}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-box">
                                    <label for="gender" class="form-label">Genre:</label>
                                    <input class="form-control" type="text" id="gender" name="gender"
                                        value="{{gender}}">
                                    <div class=" invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-box">
                                    <label for="dob" class="form-label">Birthday:</label>
                                    <input class="form-control" type="date" id="dob" name="dob" value="{{dob}}">
                                    <div class=" invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="">Adresse</h5>
                        <hr class="mb-3 mt-0">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="input-box mb-3">
                                    <label for="pays" class="form-label">Pays:</label>
                                    <select class="select_country form-control" id="pays" name="pays">
                                    </select>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="input-box mb-3">
                                    <label for="address" class="form-label">Adresse :</label>
                                    <textarea class="form-control" type="text"
                                        placeholder="Street address, P.O. box, company name, c/o" id="address"
                                        name="address">{{address}}</textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-box mb-3">
                                    <label for="ville" class="form-label">Ville:</label>
                                    <input class="form-control" type="text" id="ville" name="ville" value="{{ville}}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-box mb-3">
                                    <label for="region" class="form-label">Region:</label>
                                    <input class="form-control" type="text" id="region" name="region"
                                        value="{{region}}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-box mb-3">
                                    <label for="zip_code" class="form-label">Code Postal:</label>
                                    <input class="form-control" type="text" id="zip_code" name="zip_code"
                                        value="{{zip_code}}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="principale" name="principale"
                                        {{checked}}>
                                    <label for="principale" class="form-check-label">Définir comme adresse de livraison
                                        par
                                        defaut</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <h5 class="mt-3">Commentaire</h5>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-box mb-3">
                                    <label for="u_descr" class="form-label">Mes notes:</label>
                                    <textarea class="form-control ck_content" id="u_descr" rows="3"
                                        placeholder="Write some note.." name="u_descr">{{u_descr}}</textarea>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="row mt-1">
                            <div class="col-6">
                                <button type="submit" name="submit" id="btnAdd"
                                    class="btn btn-outline-secondary">Enregistrer
                                </button>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-box">
                        <form action="">

                        </form>
                    </div>
                </div>
                <div class="col-md-6">

                </div>
            </div>
        </div>
    </div>
</div>