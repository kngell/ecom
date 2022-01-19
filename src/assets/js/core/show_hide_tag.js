class ShowTag {
  constructor(element) {
    this.element = element;
  }
  _show = () => {
    this.element.style.display = "block";
  };
  _hide = () => {
    this.element.style.display = "none";
  };
  _toggle = () => {
    const p = this;
    if (window.getComputedStyle(p.element).display === "block") {
      p._hide();
      return;
    }
    p._show();
  };
}
export default ShowTag;
