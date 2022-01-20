import { BASE_URL, isIE } from "./config";

function get_formData(data) {
  if (Object.prototype.hasOwnProperty.call(data, "frm")) {
    const arr_frm = data.frm;
    var formData;
    if (data.frm instanceof Array) {
      formData = new FormData(arr_frm[0][0]);
      for (let i = 1; i < arr_frm.length; i++) {
        const frm = new FormData(arr_frm[i][0]);
        for (var j = 0; j < frm.length; j++) {
          formData.append(frm[j].name, frm[j].value);
        }
      }
    } else {
      formData = new FormData(arr_frm[0]);
    }
  } else {
    formData = new FormData();
  }

  // var formData = data.hasOwnProperty("frm")
  //   ? new FormData(data.frm[0])
  //   : new FormData();
  formData.append("frm_name", data.frm_name);
  formData.append("isIE", isIE());
  $.each(data, function (key, val) {
    if (key != "frm") {
      if (val instanceof Object) {
        if (key == "select2") {
          for (const [k, v] of Object.entries(val)) {
            formData.append(k, JSON.stringify(v));
          }
          // formData.append(key, JSON.stringify(val));
        } else if (key == "files") {
          for (let i = 0; i < val.length; i++) {
            formData.append(val[i].name, data.files[i]);
          }
        } else {
          if (key != "params") {
            formData.append(key, JSON.stringify(val));
          }
        }
      } else if (val instanceof Array) {
        for (let i = 0; i < val.length; i++) {
          formData.append(val[i].name, data.files[i]);
        }
      } else {
        formData.append(key, val);
      }
    }
  });
  return formData;
}
/**
 *
 * @param {*} data
 * @param {*} gestion
 */
export function Call_controller(data, gestion) {
  const formData = get_formData(data);
  $.ajax({
    url: BASE_URL + data.url,
    method: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    data: formData,
    success: function (response) {
      gestion(response, data.params ? data.params : "");
    },
    error: function (request, status, error) {
      console.log(request.responseText, error);
    },
  });
}

//delete
export function Delete(data, displayItem) {
  checkBeforeDelete(data).then((result) => {
    if (result.value) {
      $.ajax({
        url: BASE_URL + data.url,
        method: "post",
        data: data.serverData,
        dataType: "json",
        success: function (response) {
          displayItem(response, data.params ? data.params : "");
        },
      });
    }
  });
}
//function check before delete
function checkBeforeDelete(data) {
  return new Promise((resolve, reject) => {
    if (!data.url_check) {
      const html = () => {
        const htw = document.createElement("div");
        if (data.swal_message) {
          return (htw.innerHTML = data.swal_message);
        } else {
          return (htw.innerHTML = "<p>You won't be able to revert this!</p>");
        }
      };
      data.swal
        .fire({
          title: "Are you sure?",
          showCancelButton: true,
          html: html(),
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: data.swal_button ? data.swal_button : "Delete!",
        })
        .then((result) => {
          resolve(result);
        });
    } else {
      $.ajax({
        url: BASE_URL + data.url_check,
        method: "post",
        dataType: "json",
        data: data.serverData,
      })
        .done((response) => {
          data.swal
            .fire({
              title: "Are you sure?",
              showCancelButton: true,
              html: "<p>You won't be able to revert this!</p>" + response.msg,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Delete!",
            })
            .then((result) => {
              resolve(result);
            });
        })
        .fail((error) => {
          reject(error);
        });
    }
  });
}
export function displayMultisellect(data) {
  $.ajax({
    url: BASE_URL + "forms/fillMultiselect",
    method: "post",
    data: {
      table: data.table,
    },
    success: function (response) {
      if (response.result === "success") {
        data.displayID.append(response.msg);
      } else {
        data.alertID.html(response.msg);
      }
    },
  });
}

export function addCategorrie(catField, alertID) {
  let cat = document.querySelector(catField);
  if (cat.value.length == 0) {
    alert("Le champs catégorie est vide");
  } else {
    $.ajax({
      url: BASE_URL + "forms/addcategorie",
      method: "post",
      data: {
        categorie: cat.value,
      },
      success: function (response) {
        if (response != "success") {
          $(alertID).html(response);
        }
      },
    });
  }
}
export function select2AjaxParams(data) {
  var formData = get_formData(data);
  return {
    url: BASE_URL + data.url,
    type: "post",
    processData: false,
    contentType: false,
    delay: 250,
    dataType: "json",
    data: function (params) {
      formData.append("searchTerm", params.term);
      return formData;
    },
    processResults: function (response) {
      if (response.result == "success") {
        return {
          results: $.map(response.msg, function (obj) {
            return { id: obj.id, text: obj.text };
          }),
        };
      }
    },
    cache: true,
  };
}
// export function Call(data) {
//   let dt = new FormData();
//   for (const [key, value] of Object.entries(data.params)) {
//     dt.append(key, value);
//   }
//   $.ajax({
//     url: BASE_URL + data.url,
//     method: "POST",
//     processData: false,
//     contentType: false,
//     dataType: "json",
//     data: dt,
//     success: function (response) {},
//   });
// }
