<div class="col-12 transaction-commandes pt-2">
    <div class="card accordion">
        <button type="button" class="card-header accordion__button">
            <div class="row">
                <div class="col-sm-6 left-side">
                    <div class="row g-3">
                        <div class="col-md-5 cmd-date">
                            <div><span class="title">Date de Commande :</span></div>
                            <div><span class="text">{{ord_date}}</span></div>
                        </div>
                        <div class="col-md-3 cmd-amount">
                            <div><span class="title">Total :</span></div>
                            <div><span class="text">{{ord_ttc}}</span></div>
                        </div>
                        <div class="col-md-4 cmd-deliver">
                            <div><span class="title">Livré à :</span></div>
                            <div><span class="text">{{ord_userFullName}}</span></div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-6 text-end right-side">
                    <div class="row cmd-number">
                        <span class="title">N° de Commande :</span>
                        <span class="text">{{ord_number}}</span>
                    </div>
                    <div class="row details">
                        <ul>
                            <a href="" class="order-link">
                                <span class="order-details">Details de la commande</span>
                            </a>

                            <a href="" class="invoice-link">
                                <span class="invoice">Facture</span>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
        </button>
        <div class="card-body cmd-content accordion__content">
            <div class="row gx-1 delivery">
                <div class="col-md-8 delivery-infos">
                    <h5 class="card-title"><span>Livré le :</span>&nbsp;<span
                            class="date-livraison">{{ord_deliveryDate}}</span>
                    </h5>
                    <p class="card-text livraison-status">{{ord_status}}</p>
                </div>
                <div class="col-md-4 delivery-actions">
                    <a href="" class="btn mb-2">Suivre votre colis</a>
                    <a href="" class="btn">Ecrire un commentaire </a>
                </div>
            </div>
            {{ord_itemInfos}}

        </div>
    </div>
</div>