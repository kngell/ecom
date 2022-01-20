import { get_visitors_data, send_visitors_data } from "corejs/visitors";
import log_reg from "corejs/logregloader";
import "focus-within-polyfill";
import select2 from "corejs/select2_manager";
// import "smartWizard";
// import { isIE } from "corejs/config";

class HomePlugin {
  constructor(element) {
    this.element = element;
  }

  _init = () => {
    this._setupVariables();
    this._setupEvents();
  };

  _setupVariables = () => {
    this.loginBtn = this.element.find("#login_btn");
    this.header = this.element.find("#header");
    this.navigation = this.element.find(".navigation");
    this.wrapper = this.element.find(".tab-content");
  };
  _setupEvents = () => {
    var phpPlugin = this;

    //=======================================================================
    //Import login system
    //=======================================================================

    phpPlugin.header.on(
      "click show.bs.dropdown",
      ".connect .connexion",
      function (e) {
        var loader = new log_reg().check();
        if (!loader.isLoad) {
          loader.login();
          console.log("click");
        }
      }
    );

    //=======================================================================
    //Get visitors IP Adresss
    //=======================================================================
    let visitor = get_visitors_data().then((visitors_data) => {
      var data = {
        url: "visitors",
        table: "visitors",
        ip: visitors_data.ip,
      };
      send_visitors_data(data, manageR);
      function manageR(response) {}
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
document.addEventListener("DOMContentLoaded", function () {
  new HomePlugin($("#body"))._init();
});
