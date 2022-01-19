export default class _uploadAdapter {
  constructor(loader, token) {
    this.loader = loader;
    this.token = token;
  }
  upload() {
    const p = this;
    return this.loader.file.then((uploadedFile) => {
      return new Promise((resolve, reject) => {
        const data = new FormData();
        data.append("upload", uploadedFile);
        data.append("table", "post_file_url");
        data.append("allowSize", 0);
        data.append("csrftoken", p.token.csrftoken);
        data.append("frm_name", p.token.frm_name);
        $.ajax({
          url: "editorImgUrl",
          method: "post",
          data: data,
          dataType: "json",
          processData: false,
          contentType: false,
          success: function (response) {
            if (response.result == "success") {
              resolve({
                default: response.msg,
              });
            } else {
              reject(response.msg);
            }
          },
        });
      });
    });
  }
  abort() {}
}
