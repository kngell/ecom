class Comments {
  constructor(element, options) {
    this.elemnt = element;
    this.options = options;
  }
  _init = () => {
    this._setupVariables();
    this._setupEvents();
  };
  _setupVariables = () => {
    this.default = {
      page_id: 1,
      container: document.querySelector(".comments_wrapper"),
      fileUrl: "comments",
      current_pagination_page: 1,
    };
    this.options = Object.assign(this.default, this.element, this.options);
    this.csrftoken = document
      .querySelector('meta[name="csrftoken"]')
      .getAttribute("content");
    this.frm_name = "home_page";
  };
  _setupEvents = () => {
    var phpCmt = this;

    // (async () => {
    //   let url =
    //     "comments/" +
    //     "page_id=" +
    //     phpCmt.pageID +
    //     "/" +
    //     "csrftoken=" +
    //     phpCmt.csrftoken +
    //     "/" +
    //     "frm_name=" +
    //     phpCmt.frm_name; //`${phpCmt.fileUrl}?page_id=${this.pageID}`;
    //   url +=
    //     "comments_to_show" in phpCmt.options
    //       ? `&comments_to_show=${phpCmt.commentsToShow}&current_pagination_page=${phpCmt.currentPaginationPage}`
    //       : "";
    //   url += "sort_by" in phpCmt.options ? `&sort_by=${phpCmt.sortBy}` : "";
    //   fetch(url)
    //     .then((response) => response.text())
    //     .then((data) => {
    //       if (phpCmt.container) phpCmt.container.innerHTML = data;
    //       _eventHandlers();
    //     });
    // })();
    // function _eventHandlers() {}
  };
  get commentsToShow() {
    return this.options.comments_to_show;
  }
  set commentsToShow(value) {
    this.options.comments_to_show = value;
  }
  get currentPaginationPage() {
    return this.options.current_pagination_page;
  }
  set currentPaginationPage(value) {
    this.options.current_pagination_page = value;
  }
  get pageID() {
    return this.options.page_id;
  }
  set pageID(value) {
    this.options.page_id = value;
  }
  get fileUrl() {
    return this.options.url;
  }
  set fileUrl(value) {
    this.options.fileUrl = value;
  }
  get container() {
    return this.options.container;
  }
  set container(value) {
    this.options.container = value;
  }
  get sortBy() {
    return this.options.sort_by;
  }
  set sortBy(value) {
    this.options.sort_by = value;
  }
}
export default new Comments($("#comments_section"), { page_id: 1 })._init();
