class AdminPlugins {
  constructor(element) {
    this.element = element;
  }
  _init = () => {
    this._setupVariables();
    this._setupEvents();
  };
  _setupVariables = () => {
    this.wrapper = this.element.find(".page-content");
  };
  _setupEvents = () => {
    var phpPlugin = this;
  };
}
document.addEventListener("DOMContentLoaded", function () {
  new AdminPlugins($("#main-site"))._init();
});
