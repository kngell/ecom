import eL from "corejs/getParent";
class Input_Manager {
  reset_invalid_input = (form) => {
    form.find(".is-invalid").removeClass("is-invalid");
    form.find("div.invalid-feedback").html("");
  };
  //remove invalid input on focus
  removeInvalidInput(myform) {
    myform.on("focus", "input,textarea, .ck, .note-editor", function () {
      $(this).removeClass("is-invalid");
      $(this).parents(".input-box").children("div.invalid-feedback").html("");
    });
  }
  error = (form, InputErr) => {
    var arrErr = [];
    for (const [key, value] of Object.entries(InputErr)) {
      if (key == "terms") {
        const terms = document.getElementById(key);
        var div = eL
          .uPToClass(terms, "input-box")
          .querySelector(".invalid-feedback");
        div.classList.add("form-text", "d-block");
        div.innerHTML = value;
      } else {
        var input = form.find("#" + key).addClass("is-invalid");
        input
          .parents(".input-box")
          .children("div.invalid-feedback")
          .html(value);
      }
      arrErr.push(key);
    }

    return arrErr;
  };
}
export default new Input_Manager();
