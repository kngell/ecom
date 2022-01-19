//Admin js
import "plugins/flexadmin/js/app";
import { BASE_URL } from "corejs/config";
class AdminMain {
  constructor(element) {
    this.element = element;
  }
  /**
   * Init
   * =======================================================================
   */
  _init = () => {
    this._setupVariables();
    this._setupEvents();
  };
  /**
   * Setup variables
   * =======================================================================
   */
  _setupVariables = () => {
    this.wrapper = this.element.find("#main-site");
  };
  _setupEvents = () => {
    var logout = $("span:contains('Logout')");
    $(logout).on("click", function () {
      if (typeof FB !== "undefined") {
        FB.logout().then((response) => {
          // logged out
        });
      }
      $.ajax({
        url: BASE_URL + "logout",
        method: "post",
        success: function (response) {
          console.log(response);
          if (response.result == "success") {
            logout.closest("div").load(location.href + " .connect");
            if (response.msg == "redirect") {
              window.location.href = BASE_URL;
            }
          }
        },
      });
    });
  };
}
document.addEventListener("DOMContentLoaded", function () {
  new AdminMain($("body"))._init();
});
