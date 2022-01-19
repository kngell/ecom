export default class P_Toogle {
  constructor() {
    this._togglePmt();
    this._toggleBillingAddress();
  }

  _togglePmt = () => {
    let element = document.getElementById("order-payment");
    if (!element) return false;
    $(".payment-gateway-header input[type='radio']").each(function () {
      let $this = $(this);
      if (!$this.is(":checked")) {
        $this
          .closest(".payment-gateway")
          .find(".payment-gateway-content")
          .slideUp("slow");
      } else {
        $this
          .closest(".payment-gateway")
          .find(".payment-gateway-content")
          .slideToggle(0);
      }

      $this.on("change", function (e) {
        $(".payment-gateway-content").each(function () {
          if ($(".payment-gateway-content").is(":visible"));
          $(this).slideUp("slow");
        });

        if (!$this.is(":checked")) {
          $this
            .closest(".payment-gateway")
            .find(".payment-gateway-content")
            .stop()
            .slideUp("slow");
        } else {
          $this
            .closest(".payment-gateway")
            .find(".payment-gateway-content")
            .stop()
            .slideDown("slow");
        }
      });
    });
  };

  _toggleBillingAddress = () => {
    let element = document.getElementById("order-billing-address");
    if (!element) return false;
    $(".billing-address-header input[type='radio']").each(function () {
      let $this = $(this);
      if (!$this.is(":checked")) {
        $this
          .closest(".billing-address")
          .find(".billing-address-content")
          .slideUp(0);
      }
      $this.on("change", function (e) {
        $(".billing-address-content").each(function () {
          if ($(".billing-address-content").is(":visible"));
          $(this).slideUp(200);
        });

        if (!$this.is(":checked")) {
          $this
            .closest(".billing-address")
            .find(".billing-address-content")
            .stop()
            .slideUp(200);
        } else {
          $this
            .closest(".billing-address")
            .find(".billing-address-content")
            .stop()
            .slideDown(200);
        }
      });
    });
  };
}
