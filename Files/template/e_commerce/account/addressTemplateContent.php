<div class="col-xl-4">
    <div class="card {{active}}">
        <input type="hidden" name="abID" value="{{id}}">
        <div class="card-body">
            <div class="address-content">
                <ul class="address-text">
                    <li class="name"> {{prenom}} {{nom}}</li>
                    <li class="address-line">{{address}}</li>
                    <li class="zip-city"><span class="zip_code">{{code_postal}}</span><span class="city">{{ville}}
                            {{region}}</span></li>
                    <li class="state">{{pays}}</li>
                    <li class="phone">{{telephone}}</li>
                </ul>
            </div>
            <div class="manage">
                <div class="links">
                    <div class="modify-frm">
                        {{tokenmodify}}
                        <input type="hidden" name="abID" value="{{id}}">
                        <button type="button" class="modify">Modifier</button>
                    </div>
                    &#x7C;
                    <div class="erase-frm">
                        {{tokenerase}}
                        <input type="hidden" name="abID" value="{{id}}">
                        <button type="button" class="erase">Effacer</button>
                    </div>
                    &#x7C;
                    <div class="select-frm">
                        {{tokenselect}}
                        <input type="hidden" name="abID" value="{{id}}">
                        <button type="button" class="select">Selectionner</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>