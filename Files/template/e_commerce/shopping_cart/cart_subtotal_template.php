      <!-- Sub-Total section -->

      <div class="card sub-total mt-2 border">
          <div class="card-header">
              <p class="nb-item"><span class="cart_nb_elt">{{nb_items}}</span>&nbsp;<span>produits</span>
              </p>
              <p class="title"><i class="fas fa-check"></i> &nbsp; Your ordre
                  is
                  eligible for FREE Delivery</p>

          </div>
          <div class="card-body">
              <div class="total-ht">
                  <span class="title">Total HT:</span>
                  <span class="deal-price" id="deal-price">{{total_price}}</span>
                  </span>
              </div>
              <p class="transition">dont : </p>
              <ul class="cart-resume">
                  {{taxes_tempplate}}
              </ul>
              <div class="total-ttc">
                  <span class="title">Total TTC:</span>
                  <span class="total-price" id="total-price">{{total_ttc}}</span>
                  </span>
              </div>
          </div>
          <div class="card-footer proceed">
              <form class="buy-frm">
                  {{proccedd_token}}
                  <button class="button buy-btn" type="button">Proceed to
                      buy</button>
              </form>
          </div>
      </div>

      <!-- !Sub-Total section -->