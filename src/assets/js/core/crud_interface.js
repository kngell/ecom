import { Call_controller, Delete } from "corejs/form_crud";
import { AVATAR, IMG } from "corejs/config";
import input from "corejs/inputErrManager";
import Swal from "sweetalert2";
import OP from "corejs/operator";
import { htmlspecialchars_decode } from "corejs/html_decode";
import { find } from "lodash";
export default class Cruds {
  constructor(data) {
    this.table = data.table;
    this.wrapper = data.wrapper;
    this.form = data.form;
    this.modal = data.modal;
    this.select = data.select_tag;
    this.bsElement = data.bsmodal;
    this.csrftoken = data.csrftoken;
    this.frm_name = data.frm_name;
    this.ck_content = data.hasOwnProperty("ck_content") ? data.ck_content : "";
    this.loader = data.hasOwnProperty("loader") ? data.loader : "";
  }
  /**
   * Display All Items
   * ===================================================
   * @param {*} params
   */
  _displayAll = (params) => {
    const plugin = this;
    let wrapper = this.wrapper;
    var data = {
      url: "showAll",
      table: this.table,
      user: "admin",
      csrftoken: this.csrftoken,
      frm_name: this.frm_name,
    };

    Call_controller({ ...data, ...params }, (response) => {
      if (response.result == "success") {
        wrapper.find("#showAll").html(response.msg);
        if (params.datatable) _loadDatatables();
        plugin._money_format(wrapper);
      } else {
        wrapper.find("#globalErr").html(response.msg);
      }
    });

    async function _loadDatatables() {
      const DataTable = await import(
        /* webpackChunkName: "datatables" */ "datatables.net-responsive-dt"
      );
      plugin.wrapper.find("#ecommerce-datatable").DataTable({
        order: [0, "desc"],
        pagingType: "full_numbers",
        stateSave: true,
        responsive: true,
      });
    }
  };
  _money_format = (wrapper) => {
    const operation = new OP();
    operation._format_money({
      wrapper: wrapper,
      fields: [".price"],
    });
  };

  //get selected categories
  _get_selected_categories = (selector) => {
    if (selector) {
      return selector
        .map(function (i, cat) {
          if ($(this).is(":checked")) {
            return $(this).val();
          }
        })
        .get();
    } else {
      return "";
    }
  };
  _get_select2_data = (params) => {
    let select_data = [];
    $(params).each(function () {
      if ($("." + this).length != 0) {
        select_data[this] = Object.values($("." + this).select2("data"));
      }
    });
    return select_data;
  };
  /**
   * Open modal
   * =====================================================================
   */
  __open_modal = async () => {
    const bs = await import(
      /* webpackChunkName: "bsmodal" */ "corejs/bootstrap_modal"
    );
    new bs.default(["modal-box"])._init().then((modal) => {
      modal[0].show();
    });
  };
  /**
   * add or update
   * @param {*} params
   * =======================================================================
   */
  _add_update = (params) => {
    let plugin = this;
    var data = {
      url: plugin.form.find("#operation").val() === "add" ? "Add" : "update",
      frm: plugin.form,
      frm_name: params.frm_name,
      table: plugin.table,
    };
    params.hasOwnProperty("imageUrlsAry")
      ? (data.imageUrlsAry = params.imageUrlsAry)
      : "";
    plugin.hasOwnProperty("select")
      ? (data.select2 = plugin._get_select2_data(plugin.select))
      : "";
    params.hasOwnProperty("categorie")
      ? (data.categories = plugin._get_selected_categories(params.categorie))
      : "";
    params.hasOwnProperty("folder") ? (data.folder = params.folder) : "";
    params.hasOwnProperty("validator_rules")
      ? (data.validator_rules = params.validator_rules)
      : "";
    switch (plugin.form.find("#operation").val()) {
      case "add":
      case "update":
        if (params.hasOwnProperty("dropzone")) {
          Call_controller(
            { ...data, ...{ files: params.dropzone.files } },
            manageR
          );
        } else {
          Call_controller(data, manageR);
        }
        break;
    }
    function manageR(response) {
      plugin.form.find("#submitBtn").val("Submit");
      switch (response.result) {
        case "error-field":
          input.error(plugin.modal, response.msg);
          break;
        case "success":
          plugin.form.trigger("reset");
          if (plugin.modal) {
            (async () => {
              const bs = await import(
                /* webpackChunkName: "bsmodal" */ "corejs/bootstrap_modal"
              );
              new bs.default([plugin.bsElement])._init().then((modal) => {
                modal[0].hide();
              });
            })();
            if (params.swal) {
              Swal.fire("Success!", response.msg, "success").then(() => {
                if (params.datatable == true) {
                  const {
                    frm_name,
                    frm,
                    categorie,
                    dropzone,
                    ...dysplayparams
                  } = params;
                  plugin._displayAll(dysplayparams);
                }
                if (plugin.table == "users") {
                  if (plugin.hasOwnProperty("tag")) {
                    plugin.tag
                      .parents(".card-footer")
                      .siblings(".card-body")
                      .find(".img-wrapper img")
                      .attr("src", response.url);
                  }
                }
              });
            }
          }

          if (params.prepend) {
            params.nested.prepend(response.msg);
          } else {
            if (params.prepend === false) {
              params.nested.before(response.msg);
              params.nested.hide();
            }
          }
          break;
        case "error-file":
          if (typeof params.dropzone != "undefined") {
            params.dropzone._manageErrors(response.msg);
            params.dropzone._removeErrMsg();
          } else {
            plugin.form.find("#alertErr").html(response.msg);
            plugin.form.trigger("reset");
          }
          break;
        default:
          plugin.form.find("#alertErr").html(response.msg);
          plugin.form.trigger("reset");
          break;
      }
    }
  };

  //=======================================================================
  //Get Id section
  //=======================================================================
  // Get edit id
  _get_Edit_id = (selector) => {
    let table = this.table;
    let result;
    switch (table) {
      case "users":
        result = selector
          .parents(".action")
          .children(".delete_user")
          .find("input[name='userID']")
          .val();
        break;

      default:
        result = selector.attr("id");
        break;
    }
    return result;
  };
  /**
   * Clean Params
   * ======================================================
   * @param {*} params
   * @returns
   */
  _clean_params = (params) => {
    let ajax_param = {};
    const exclude = [
      "std_fields",
      "inputElement",
      "dropzone",
      "categorieElement",
    ];
    for (const [k, v] of Object.entries(params)) {
      if (!exclude.includes(k)) {
        ajax_param[k] = v;
      }
    }
    return ajax_param;
  };
  /**
   * Edit forms
   * ===============================================================================
   * @param {*} params
   */
  _edit = (params) => {
    let plugin = this;
    var data = {
      url: "edit",
      frm: params.frm,
      id: plugin._get_Edit_id(params.tag),
      table: params.table,
      frm_name: params.frm_name,
      params: params.hasOwnProperty("std_fields") ? params.std_fields : "",
    };
    const ajax_params = plugin._clean_params(params);
    Call_controller({ ...data, ...ajax_params }, (response, std_fields) => {
      if (response.result === "success") {
        params.hasOwnProperty("tag") ? (plugin.tag = params.tag) : "";
        $(std_fields).each(function (i, field) {
          switch (true) {
            case plugin.ck_content != "" && plugin.ck_content.includes(this):
              if (plugin.hasOwnProperty("loader")) {
                plugin.loader.editor[this].data.set(response.msg.items[field]);
              }
              break;
            case $("#" + this).hasClass("select2-hidden-accessible"):
              let select_field = this;
              if (response.msg.selectedOptions.hasOwnProperty(select_field)) {
                if (response.msg.selectedOptions[select_field].length != 0) {
                  $(response.msg.selectedOptions[select_field][0]).each(
                    function () {
                      let select = plugin.form.find("." + select_field);
                      if (
                        !select.find("option[value='" + this.id + "']").length
                      ) {
                        select.append(
                          new Option(this.text, this.id, false, true)
                        );
                        select.val(
                          response.msg.selectedOptions[select_field][1]
                        );
                        select.trigger("change");
                      }
                    }
                  );
                }
              }
              break;
            case this == "p_media" &&
              ["products", "sliders", "posts"].includes(plugin.table):
              if (response.msg.items[field]) {
                var dz = params.dropzone;
                $(dz.element).find(".message").hide();
                dz.files = [];
                $.each(response.msg.items[field], function (key, value) {
                  let gallery_item = dz._createGallery(value);
                  dz._createFile(value)
                    .then((file) => {
                      dz.files.push(file);
                      dz._createExtraDiv(file, gallery_item);
                    })
                    .catch(function (error) {
                      console.log(
                        "Il y a eu un problème avec l'opération fetch: " +
                          error.message
                      );
                    });
                  dz.element.find(".gallery").append(gallery_item);
                  dz.element.on("click", ".gallery_item", (e) => {
                    e.stopPropagation();
                  });
                });
                dz._removeFiles();
              }

              break;
            case this == "profileImage" && plugin.table == "users":
              plugin.modal
                .find(".upload-box .img")
                .attr("src", IMG + response.msg.items[field]);
              break;
            default:
              if ($("#" + this).is("input")) {
                if ($("#" + this).is(":checkbox")) {
                  if (response.msg.items[field] == "on") {
                    $("#" + this).prop("checked", true);
                  } else {
                    $("#" + this).prop("checked", false);
                  }
                } else {
                  $("#" + this).val(response.msg.items[field]);
                }
              } else {
                $("#" + this).html(response.msg.items[field]);
              }
              break;
          }
        });

        if (response.msg.selectedOptions.hasOwnProperty("categorie")) {
          if (response.msg.selectedOptions["categorie"].length > 0) {
            if (params.hasOwnProperty("categorieElement")) {
              response.msg.selectedOptions["categorie"][1].forEach((cat) => {
                params.categorieElement
                  .find("input[value='" + cat + "']")
                  .prop("checked", true);
              });
            }
          }
        }
        if (plugin.form) plugin._money_format(plugin.form);
      } else {
        if (plugin.form.find("#tbl-alertErr").length != 0) {
          plugin.form.find("#tbl-alertErr").html(response.msg);
        } else {
          plugin.form.find("#alertErr").html(response.msg);
        }
      }
    });
  };
  //=======================================================================
  //Delete
  //=======================================================================
  _get_delete_data = (selector, params) => {
    let table = this.table;
    let result;
    let id;
    switch (table) {
      case "users":
        id = selector.parent().find("input[name=userID]").val();
        break;
      default:
        id = selector.attr("id");
        break;
    }
    if (!params.hasOwnProperty("frm")) {
      result = {
        table: table,
        frm_name: selector.attr("id"),
        id: id ? id : "",
        csrftoken: this.csrftoken,
        method: params.hasOwnProperty("method") ? params.method : "",
        folder: params.hasOwnProperty("folder") ? params.folder : "",
        del_method: params.hasOwnProperty("del_method")
          ? params.del_method
          : "",
      };
    } else {
      result =
        selector.find("input[type='hidden']").serialize() +
        "&" +
        $.param({
          table: table,
          frm_name: selector.attr("id"),
          id: id ? id : "",
          method: params.hasOwnProperty("method") ? params.method : "",
          folder: params.hasOwnProperty("folder") ? params.folder : "",
          del_method: params.hasOwnProperty("del_method")
            ? params.del_method
            : "",
        });
    }
    return result;
  };

  _delete = (params) => {
    let plugin = this;
    plugin.wrapper.on("submit", params.delete_frm_class, function (e) {
      e.preventDefault();
      const swal = params.hasOwnProperty("swal") && params.swal ? Swal : false;
      var data = {
        url: "delete",
        swal: swal,
        serverData: plugin._get_delete_data($(this), params),
        url_check: params.hasOwnProperty("url_check") ? params.url_check : "",
      };
      Delete(data, (response) => {
        if (response.result === "success") {
          if (params.hasOwnProperty("swal") && params.swal) {
            Swal.fire("Deleted!", response.msg, "success").then(() => {
              if (params.hasOwnProperty("datatable") && params.datatable) {
                plugin._displayAll(params);
              } else {
                location.reload();
              }
            });
          }
        } else {
          if (plugin.form.find("#alertErr").length == 0) {
            plugin.form.find("#tbl-alertErr").html(response.msg);
          } else {
            plugin.form.find("#alertErr").html(response.msg);
          }
        }
      });
    });
  };

  //=======================================================================
  //Restore
  //=======================================================================

  _restore = (params) => {
    let plugin = this;
    plugin.wrapper.on("submit", params.restore_frm_class, function (e) {
      e.preventDefault();
      var data = {
        url: "delete",
        swal: Swal,
        swal_button: params.swal_button,
        swal_message: params.swal_message,
        serverData: plugin._get_delete_data($(this), params),
      };
      Delete(data, manageR);
      function manageR(response) {
        if (response.result === "success") {
          if (params.swal) {
            Swal.fire("Restore!", response.msg, "success").then(() => {
              if (params.datatable == true) {
                plugin._displayAll({ datatable: params.datatable });
              } else {
                location.reload();
              }
            });
          }
        } else {
          plugin.form.find("#alertErr").html(response.msg);
        }
      }
    });
  };

  /**
   * Clean Forms
   * ==============================================================
   * @param {*} data
   */
  _clean_form = (data = {}) => {
    const select = data.select ? data.select : this.select;
    const plugin = this;
    //remove invalid input on input focus
    input.removeInvalidInput(plugin.modal);
    //clean form on hide

    document
      .getElementById(plugin.bsElement)
      .addEventListener("hide.bs.modal", function () {
        if (plugin.modal.find(".is-invalid").length != 0) {
          input.reset_invalid_input(plugin.modal);
        }
        if (data.hasOwnProperty("cke") && data.cke == true) {
          $.each(plugin.ck_content, (idx, content) => {
            plugin.loader.editor[content].setData("");
          });
        }
        if (data.hasOwnProperty("inputHidden")) {
          $.each(data.inputHidden, (idx, input) => {
            $("#" + input).val("");
          });
        }
        plugin.form[0].reset();
        if (select != "") {
          if (Array.isArray(select)) {
            $(select).each(function (i, tag) {
              plugin.modal.find("#" + tag).empty();
              plugin.modal.find("#" + tag).trigger("input");
            });
          } else {
            plugin.modal.find(select).empty();
            plugin.modal.find(select).trigger("input");
          }
        }
        data.upload_img
          ? plugin.modal.find(data.upload_img).attr("src", AVATAR)
          : "";
        plugin.modal.find("input[type='checkbox']").empty();
        plugin.modal.find("textarea").empty();
        if (data.hasOwnProperty("dropzone")) {
          $(data.dropzone.element)
            .find(".gallery-wrapper .gallery_item")
            .remove();
          $(data.dropzone.element).find(".message").show();
        }
        if (data.hasOwnProperty("select")) {
          $(data.select).each(function () {
            plugin.form.find("." + this).empty();
          });
        }
      });
  };

  _cleanTempFiles = (params = {}) => {
    Call_controller(params, (response) => {
      if (response.result == "success") {
        console.log(response.msg);
      }
    });
  };
  // =======================================================================
  // Active/desactive plugin
  // =======================================================================
  _active_inactive_elmt = (params) => {
    let wrapper = this.wrapper;
    wrapper.on("click", ".activateBtn", function (e) {
      e.preventDefault();
      var data = {
        url: "updateFromTable",
        table: params.table,
        frm: $(this).parents("form"),
        frm_name: $(this).parents("form").attr("id"),
        method: "updateStatus",
        params: $(this),
      };
      Call_controller(data, Response);
      function Response(response, elmt) {
        if (response.result == "success") {
          response.msg == "green"
            ? elmt.attr("title", "Deactivate Category")
            : elmt.attr("title", "Activate Category");
          elmt
            .children()
            .first()
            .attr("style", "color:" + response.msg);
        } else {
          if (wrapper.find("#tbl-alertErr").length != 0) {
            wrapper.find("#tbl-alertErr").html(response.msg);
          } else {
            wrapper.find("#alertErr").html(response.msg);
          }
        }
      }
    });
  };
}
