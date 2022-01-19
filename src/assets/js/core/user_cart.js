import { Call_controller } from "corejs/form_crud";
import OP from "corejs/operator";
export default class User_cart {
  constructor(wrapper, header) {
    this.wrapper = wrapper;
    this.header = header;
  }

  //=======================================================================
  //Display user cart items
  //=======================================================================
  _display_cart = () => {
    let plugin = this;
    const csrftoken = document.querySelector('meta[name="csrftoken"]');
    var data = {
      table: "cart",
      user: "guest",
      url: "showDetails",
      data_type: "template",
      csrftoken: csrftoken ? csrftoken.getAttribute("content") : "",
      frm_name: "all_product_page",
    };
    const operation = new OP();
    Call_controller(data, display_cart);
    function display_cart(response) {
      if (response.result == "success") {
        plugin.wrapper.find(".cart_nb_elt").html(function () {
          return response.msg[0];
        });
        if (response.msg[1]) {
          plugin.wrapper.find("#cart_items").fadeOut(100, function () {
            return plugin.wrapper
              .find("#cart_items")
              .html(response.msg[1])
              .fadeIn()
              .delay(500);
          });
        }
        if (plugin.wrapper.find("#cart_items").length) {
          plugin.header.find(".cart_nb_elt").html(function () {
            return response.msg[0];
          });

          plugin.wrapper.find("#sub_total").fadeOut(100, function () {
            return plugin.wrapper
              .find("#sub_total")
              .html(response.msg[2])
              .fadeIn()
              .delay(500); // display Cart items
          });
        }

        if (response.msg[3]) {
          plugin.wrapper.find("#wishlist-items").html(function () {
            return response.msg[3]; // display wishlist
          });
          plugin.wrapper.find("#wishlist").show().fadeIn().delay(500);
        }
        setTimeout(function () {
          operation._format_money({
            wrapper: plugin.wrapper,
            fields: [
              "#deal-price",
              ".product_price",
              ".res-tax-item .value",
              "#total-price",
            ],
          });
        }, 500);
      }
    }
  };
}
