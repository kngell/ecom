    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
        <div class="card bg-light w-100">
            <div class="card-header text-muted border-bottom-0">
                {{function}}
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-7">
                        <h2 class="lead"><b>{{firstname}}&nbsp;{{lastname}}</b>
                        </h2>
                        <ul class="ms-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span>
                                <b>Address:</b>
                                {{address}}
                            </li>
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <b>Phone
                                    #:</b> {{phone}}
                            </li>
                        </ul>
                    </div>
                    <div class="col-5 text-center img-wrapper">
                        <img src="{{image}}" alt="user-avatar" class="img-circle img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex">
                <div class="row justify-content-between w-100 g-2">
                    <div class="col text-start">
                        <a href="{{users_profile}}" class="btn btn-sm btn-primary">
                            <i class="fas fa-user"></i> Edit Profile
                        </a>
                        <a href="#" class="btn btn-sm bg-teal">
                            <i class="fas fa-comments"></i>
                        </a>
                        <div id="alertErr"></div>
                    </div>
                    <div class="col text-end pt-2 action">
                        <form id="restore_user{{userID}}" class="restore_user pe-1 me-2" {{style_restore}}>
                            {{token_r}}
                            <input type="hidden" name='userID' value="{{userID}}">
                            <input type="hidden" name='user-method' value="{{method}}">
                            <button type="submit" class="{{delBtnClass}} restoreBtn" title="Restore"><i
                                    class="fad fa-trash-undo-alt fa-lg"></i></button>
                        </form>
                        <form id="edit-user-frm{{userID}}" class="edit-user-frm">
                            {{token_e}}
                            <input type="hidden" name='userID' value="{{userID}}">
                            <input type="hidden" name='user-method' value="{{method}}">
                            <div id="edit_user{{userID}}" class="edit-user" {{style_edit}}>
                                <button type="button" class="editBtn" data-bs-toggle="modal" data-bs-target="#modal-box"
                                    title="Edit"> <i class="far fa-edit fa-lg"></i></button>
                            </div>
                        </form>
                        <form id="delete_user{{userID}}" class="delete_user">
                            {{token_d}}
                            <input type="hidden" name='userID' value="{{userID}}">
                            <input type="hidden" name='user-method' value="{{method}}">
                            <button type="submit" class="{{delBtnClass}} deleteBtn"><i
                                    class="fas fa-trash-alt fa-lg"></i></button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>