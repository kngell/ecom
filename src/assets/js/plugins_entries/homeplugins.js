import { get_visitors_data, send_visitors_data } from "corejs/visitors";
import log_reg from "corejs/logregloader";
import "focus-within-polyfill";
import select2 from "corejs/select2_manager";
class HomePlugin {
  constructor(element) {
    this.element = element;
  }

  _init = (e) => {
    this._setupVariables();
    this._setupEvents(e);
  };

  _setupVariables = () => {
    this.loginBtn = this.element.find("#login_btn");
    this.header = this.element.find("#header");
    this.navigation = this.element.find(".navigation");
    this.wrapper = this.element.find(".tab-content");
  };
  _setupEvents = (event) => {
    var phpPlugin = this;
    /**
     * Login and Register
     * ------------------------------------------------------
     */
    phpPlugin.header.on(
      "click show.bs.dropdown",
      ".connect .connexion",
      function (e) {
        var loader = new log_reg().check();
        if (!loader.isLoad) {
          loader.isLoadStatus(true);
          loader.login();
        }
      }
    );

    /**
     * Get Visitors data
     */
    let visitor = get_visitors_data(event).then((visitors_data) => {
      var data = {
        url: "visitors",
        table: "visitors",
        ip: visitors_data.ip,
      };
      send_visitors_data(data, (response) => {
        console.log(response);
      });
    });
    //=======================================================================
    //Ajax Select2
    //=======================================================================
    //Activate select2 box for contries
    const select = new select2();
    const csrftoken = document.querySelector('meta[name="csrftoken"]');
    select._init({
      element: phpPlugin.wrapper.find(".select_country"),
      placeholder: "Sélectionnez un pays",
      url: "guests/get_countries",
      csrftoken: csrftoken ? csrftoken.getAttribute("content") : "",
      frm_name: "all_product_page",
    });

    // window.onbeforeunload = function () {
    //   websocket.onclose = function () {}; // disable onclose handler first
    //   websocket.close();
    // };
  };
}
document.addEventListener("DOMContentLoaded", function (e) {
  new HomePlugin($("#body"))._init(e);
});
