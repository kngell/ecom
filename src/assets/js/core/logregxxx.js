import { BASE_URL } from "corejs/config";
import { Add, Call_controller, ManageResponse } from "corejs/form_crud";
import { reset_invalid_input } from "corejs/inputErrManager";
import { readurl } from "corejs/profile_img";
function PhpLR(element) {
  this.element = element;
  this.init();
}
PhpLR.prototype.init = function () {
  this.setupVariables();
  this.setupEvents();
};
//PHP LR setup Variables
PhpLR.prototype.setupVariables = function () {
  this.logbox = this.element.find("#login-box");
  this.loginfrm = this.element.find("#login-frm");
  this.regbox = this.element.find("#register-box");
  this.regfrm = this.element.find("#register-frm");
  this.forgotbox = this.element.find("#forgot-box");
  this.forgotfrm = this.element.find("#forgot-frm");
};
//PHP LR setup Events
PhpLR.prototype.setupEvents = function () {
  var phpLR = this;

  //refresh login & register frm on hide and show
  phpLR.logbox.on("hide.bs.modal", function () {
    if (phpLR.loginfrm.find(".is-invalid").length != 0) {
      reset_invalid_input(phpLR.loginfrm);
    }
  });
  //Reset register form invalid input on hide modal
  phpLR.regbox.on("hide.bs.modal", function () {
    if (phpLR.regfrm.find(".is-invalid").length != 0) {
      reset_invalid_input(phpLR.regfrm);
    }
  });
  //Reset register form on shown
  phpLR.regbox.on("show.bs.modal", function () {
    phpLR.regfrm.trigger("reset");
    phpLR.regfrm.find("#regAlert").html("");
  });
  //remove invalid input on focus register
  phpLR.regfrm.on("focus", "input", function () {
    $(this).removeClass("is-invalid");
    $(this).parent().children("div.invalid-feedback").html("");
  });
  //remove invalid input on focus login
  phpLR.loginfrm.on("focus", "input", function () {
    $(this).removeClass("is-invalid");
    $(this).parent().children("div.invalid-feedback").html("");
  });
  //reset forgot password frm
  phpLR.forgotbox.on("hide.bs.modal", function () {
    phpLR.forgotfrm.trigger("reset");
  });
  //Fill in login from cookies on shonw
  phpLR.logbox.on("shown.bs.modal", function () {
    $.ajax({
      url: BASE_URL + "auth/remember_check",
      method: "post",
      success: function (response) {
        if (response.result === "success") {
          phpLR.loginfrm.find("#email").val(response.msg.email);
          phpLR.loginfrm
            .find("#customCheck")
            .attr("checked", response.msg.remember);
        } else {
          phpLR.loginfrm.find("#email").val("");
          phpLR.loginfrm.find("#password").val("");
        }
      },
    });
  });
  //Register form
  phpLR.regfrm.on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();
    phpLR.regfrm.find("#register-btn").val("Please wait...");
    var inputData = {
      url: "auth/ajaxRegister",
      frm: phpLR.regfrm,
      table: "auth",
      notification: "admin",
      frm_name: "register-frm",
    };
    Add(inputData, gestionR);
    function gestionR(response) {
      console.log(response);
      phpLR.regfrm.find("#register-btn").val("Enregistrer");
      var outputData = {
        frm: phpLR.regfrm,
        swal: false,
        alertid: phpLR.regfrm.find("#regAlert"),
        display: false,
        imgarea: phpLR.regbox.find(".upload-profile-image .img"),
        imgvalue: response.img ? response.img : "",
        alertsuccess: true,
      };
      ManageResponse(response, outputData);
    }
  });

  //read profile image on change
  phpLR.regbox
    .find('.upload-profile-image input[type="file"]')
    .on("change", function () {
      readurl(
        this,
        phpLR.regbox.find(".upload-profile-image .img"),
        phpLR.regbox.find(".upload-profile-image .camera-icon")
      );
    });
  //Login form

  phpLR.loginfrm.on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();
    phpLR.loginfrm.find("#login-btn").val("Please wait...");
    var data = {
      url: "auth/ajaxLogin",
      frm: phpLR.loginfrm,
      frm_name: "login-frm",
    };
    Call_controller(data, ManageLoginResponse);
    //manage login Response
    function ManageLoginResponse(response) {
      phpLR.loginfrm.find("#login-btn").val("Login");
      var rdata = {
        frm: phpLR.loginfrm,
        btn: phpLR.loginfrm.find("#login-btn"),
        textBtn: "Sign In",
        alertid: phpLR.loginfrm.find("#loginAlert"),
        loginbox: phpLR.logbox,
        login: true,
      };
      ManageResponse(response, rdata);
    }
  });

  //login with Facebook
  phpLR.logbox.find("#fblink").on("click", (e) => {
    e.preventDefault();
    phpLR.loginfrm.find("#login-btn").val("Please wait...");
    var data = {
      frm: phpLR.loginfrm,
      url: "guests/fbLogin",
      frm_name: "login-frm",
    };
    Call_controller(data, FbResponse);
    function FbResponse(response) {
      if (response.result == "success") {
        FB = facebooklogin(response);
      } else {
        phpLR.loginfrm.find("#loginAlert").html(response.msg);
      }
    }
  });
  //facebook login
  function facebooklogin(resp) {
    Facebook.load().then(() => {
      Facebook.init({
        appId: resp.msg.appID,
      });
      Facebook.getLoginStatus().then((response) => {
        if (response.status === "connected") {
          getFbUserData();
        } else {
          fbLogin();
        }
      });
    });
    return Facebook;
  }

  // Facebook login with JavaScript SDK
  function fbLogin() {
    FB.login({
      scope: "email",
      return_scopes: true,
    }).then((response) => {
      if (response.status === "connected") {
        getFbUserData();
      } else {
        document.getElementById("loginAlert").innerHTML =
          "User cancelled login or did not fully authorize.";
      }
    });
  }
  // // Fetch the user profile data from facebook
  function getFbUserData() {
    FB.api("/me", "get", {
      fields: "id,first_name,last_name,email,link,gender,locale,picture",
    }).then((response) => {
      var data = {
        frm: phpLR.loginfrm,
        frm_name: "login-frm",
        url: "auth/fblogin",
        freedata: JSON.stringify(response),
      };
      Call_controller(data, ManageLoginResponse);
    });
  }
  //Forgot password request
  phpLR.forgotfrm.on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();
    phpLR.forgotfrm.find("#forgot-btn").val("Please wait...");
    var data = {
      url: "auth/forgot",
      frm: phpLR.forgotfrm,
      frm_name: "forgot-frm",
    };
    Call_controller(data, MResponse);
    function MResponse(response) {
      var rdata = {
        frm: phpLR.forgotfrm,
        alertid: phpLR.forgotfrm.find("#forgotAlert"),
        alertsuccess: true,
      };
      ManageResponse(response, rdata);
      phpLR.forgotfrm.find("#forgot-btn").val("Reset password");
    }
  });
  //logout
  var logout = $("a:contains('Logout')");

  $(logout).on("click", function () {
    if (typeof FB !== "undefined") {
      FB.logout().then((response) => {
        // logged out
      });
    }
    $.ajax({
      url: BASE_URL + "auth/logout",
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
  //----------------------Ajax logout from elsewhere
};
$.fn.phpLR = function (options) {
  new PhpLR(this);
  return this;
};
$("#Login-Register-System").phpLR();
