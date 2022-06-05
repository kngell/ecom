import { Call_controller } from "corejs/form_crud";
import input from "corejs/inputErrManager";

class EmailVerification {
  constructor(element) {
    this.element = element;
  }
  _init = () => {
    this._setupVariables();
    this._setupEvents();
  };
  _setupVariables = () => {
    this.form = this.element.find("#verify-frm");
  };
  _setupEvents = () => {
    var plugin = this;

    /**
     * remove invalid input on focus
     */
    input.removeInvalidInput(plugin.form);
    /**
     * Submit Emai Verification
     */
    plugin.form.on("submit", function (e) {
      e.preventDefault();
      $(this).find("#verify-btn").val("Please wait...");
      var inputData = {
        url: "verify",
        frm: $(this),
        frm_name: $(this).attr("id"),
      };
      Call_controller(inputData, (response) => {
        plugin.form.find("#verify-btn").val("Send Link");
        if (response.result == "success") {
          plugin.form.find("#alertErr").html(response.msg);
        } else {
          if (response.result == "error-field") {
            input.error(plugin.form, response.msg);
          } else {
            plugin.form.find("#alertErr").html(response.msg);
          }
        }
      });
    });
  };
}
document.addEventListener("DOMContentLoaded", function () {
  new EmailVerification($("#main-site"))._init();
});
