export default class StripeAPIClient {
  constructor(params = {}) {
    this.api_key = params.api_key; // ok
    this.cardHolderFname = params.cardHolderFname; //ok
    this.cardHolderLname = params.cardHolderLname; //ok
    this.cardElement = params.cardElement; //ok
    this.cardExp = params.cardExp;
    this.cardCvc = params.cardCvc;
    this.cardError = params.cardError; //ok
    this.cardErrorID = params.cardErrorID;
    this.cardButton = params.cardButton; //ok
    this.cardButtonID = params.cardButtonID;
    this.responseError = params.responseError; //ok
  }
  /**
   * Manage Button
   * ======================================================================================
   */
  _create_cardElements = () => {
    const plugin = this;
    // document.querySelector(plugin.cardButtonID).disabled = true;
    const stripe = Stripe(plugin.api_key);
    var style = {
      base: {
        backgroundColor: "transparent",
        color: "#333",
        fontSize: "20.8px",
        iconColor: "rgba(126,128,251)",
        fontFamily: "share_tech,sans-serif",
        fontSmoothing: "antialiased",
        "::placeholder": {
          color: "#757593",
        },
      },
      invalid: {
        fontFamily: "sans-serif",
        color: "#fa755a",
        iconColor: "#fa755a",
      },
      complete: { color: "green" },
    };
    const elements = stripe.elements();
    const card = elements.create("cardNumber", {
      style: style,
      showIcon: true,
      iconStyle: "solid",
      placeholder: "1234 1234 1234 1234",
    });
    const cardExp = elements.create("cardExpiry", {
      disabled: true,
      style: style,
    });
    const cardCvc = elements.create("cardCvc", {
      disabled: true,
      style: style,
    });
    card.mount(plugin.cardElement);
    cardExp.mount(plugin.cardExp);
    cardCvc.mount(plugin.cardCvc);
    card.on("change", function (e) {
      if (e.complete) {
        cardExp.update({ disabled: false });
        cardExp.focus();
      }
    });
    cardExp.on("change", function (e) {
      if (e.complete) {
        cardCvc.update({ disabled: false });
        cardCvc.focus();
      }
    });
    cardCvc.on("change", function (e) {
      if (e.complete) {
        plugin.cardButton.disabled = false;
      }
    });
    plugin._manage_errors(card, cardExp, cardCvc);
    plugin.card = card;
    plugin.stripe = stripe;
    return plugin;
  };

  /**
   * Manage Errors
   * ======================================================================================
   */
  _manage_errors = (card, cardExp, cardCvc) => {
    const plugin = this;
    [card, cardExp, cardCvc].forEach((elt, index) => {
      elt.addEventListener("change", (e) => {
        if (e.error) {
          plugin.cardError.textContent = e.error.message;
        } else {
          plugin.cardError.textContent = "";
        }
      });
    });
  };
  /**
   * Create Paiment
   * ======================================================================================
   */
  _createPayment = () => {
    const plugin = this;
    return new Promise((resolve, reject) => {
      plugin.stripe
        .createPaymentMethod({
          type: "card",
          card: plugin.card,
          billing_details: {
            // Include any additional collected billing details.
            name:
              plugin.cardHolderFname.value + " " + plugin.cardHolderLname.value,
          },
        })
        .then((response) => {
          if (response.error) {
            reject(response);
          } else {
            resolve(response);
          }
        });
    });
  };
}
