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
  _setupEvents = (e) => {
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
  };
}
document.addEventListener("DOMContentLoaded", function (e) {
  new HomePlugin($("#body"))._init(e);
  (function () {
    if (typeof EventTarget !== "undefined") {
      let supportsPassive = false;
      try {
        // Test via a getter in the options object to see if the passive property is accessed
        const opts = Object.defineProperty({}, "passive", {
          get: () => {
            supportsPassive = true;
          },
        });
        window.addEventListener("testPassive", null, opts);
        window.removeEventListener("testPassive", null, opts);
      } catch (e) {}
      const func = EventTarget.prototype.addEventListener;
      EventTarget.prototype.addEventListener = function (type, fn) {
        this.func = func;
        this.func(type, fn, supportsPassive ? { passive: false } : false);
      };
    }
  })();
});
