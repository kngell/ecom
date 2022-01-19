<div class="title">
    <h2>{{title}} </h2>
    <a href="#" class="btn btn-light d-none" id="back"><span class="icon back"></span>&nbsp;</i>&nbsp;Back
    </a>
    {{session_data}}
</div>
<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                    data-target="#objectifs" aria-expanded="true" aria-controls="collapseOne">
                    <h3 class="sub-title">Objectifs</h3>
                </button>
            </h2>
        </div>

        <div id="objectifs" class="collapse show objectifs" aria-labelledby="headingOne"
            data-parent="#accordionExample">
            <div class="card-body ck-content">
                {{objectifs}}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#stagiaire" aria-expanded="false" aria-controls="collapseTwo">
                    <h3 class="sub-title">Profil statagiaire</h3>
                </button>
            </h2>
        </div>
        <div id="stagiaire" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body ck-content">
                {{statagiaire}}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#programme" aria-expanded="false" aria-controls="collapseThree">
                    <h3 class="sub-title">Programme</h3>
                </button>
            </h2>
        </div>
        <div id="programme" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body ck-content">
                {{programme}}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#pre-requis" aria-expanded="false" aria-controls="collapseThree">
                    <h3 class="sub-title">Pre-requis</h3>
                </button>
            </h2>
        </div>
        <div id="pre-requis" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">
                {{pre-requis}}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#animateur" aria-expanded="false" aria-controls="collapseThree">
                    <h3 class="sub-title">Profil animateur</h3>
                </button>
            </h2>
        </div>
        <div id="animateur" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">
                {{animateur}}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#method-pedagogique" aria-expanded="false" aria-controls="collapseThree">
                    <h3 class="sub-title">Méthode pédagogique</h3>
                </button>
            </h2>
        </div>
        <div id="method-pedagogique" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body ck-content">
                {{method}}
            </div>
        </div>
    </div>
</div>
<div id="session_id" style="display:none">{{session_id}}</div>