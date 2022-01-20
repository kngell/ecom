import { select2AjaxParams } from "corejs/form_crud";
export default class Upload {
  constructor(params = {}) {
    this.params = params;
  }

  //=======================================================================
  //Manage select tag
  //=======================================================================
  _init = (params = {}) => {
    let plugin = this;
    var data = {};
    for (let [key, value] of Object.entries(params)) {
      if (!(value instanceof Object)) {
        if (key == "tbl_options") {
          key = "table";
        }
        data[`${key}`] = `${value}`;
      }
    }
    data["data_type"] = "select2";
    this.params.hasOwnProperty("parentID")
      ? (data.parentID = this.params.parentID)
      : "";
    let select = params.element
      .select2({
        placeholder: "---" + params.placeholder + "---",
        maximumSelectionLength: 2,
        tags: true,
        // tokenSeparators: [";", "\n", "\t"],
        allowClear: true,
        width: "100%",
        ajax: select2AjaxParams(data),
        dropdownParent: params.element.parent(),
      })
      .on("select2:close", function () {
        $(this)
          .removeClass("is-invalid")
          .parent()
          .find(".invalid-feedback")
          .html("");
      });

    plugin.select = select;
    return plugin;
  };
  _destroy = () => {
    const plugin = this;
    if (this.select.hasClass("select2-hidden-accessible")) {
      this.select.select2("destroy");
    }
    return plugin;
  };
}
