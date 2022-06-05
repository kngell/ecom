//Get visitors Data

export const get_visitors_data = () => {
  // e.preventDefault();
  return new Promise((resolve, reject) => {
    let data = {
      ip: $("#ip_address").val(),
    };
    if (data) {
      resolve(data);
    } else {
      reject("no data");
    }
  });
};

export const send_visitors_data = (data, manageR) => {
  $.ajax({
    url: data.url,
    method: "post",
    dataType: "json",
    data: {
      table: data.table,
      ip: data.ip ? data.ip : "",
      cookies: data.cookies ? data.cookies : "",
      csrftoken: document
        .querySelector('meta[name="csrftoken"]')
        .getAttribute("content"),
      frm_name: "home_page",
    },
  })
    .done((response) => {
      manageR(response);
    })
    .fail((error) => {
      console.log(error);
    });
};
