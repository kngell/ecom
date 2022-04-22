class GetParent {
  upToTag = (el, tagName) => {
    tagName = tagName.toLowerCase();

    while (el && el.parentNode) {
      el = el.parentNode;
      if (el.tagName && el.tagName.toLowerCase() == tagName) {
        return el;
      }
    }
    // Many DOM methods return null if they don't
    // find the element they are searching for
    // It would be OK to omit the following and just
    // return undefined
    return null;
  };
  uPToClass = (ele, parentClass = "parent") => {
    var e = ele;
    while (!e.classList.contains(parentClass)) {
      e = e.parentElement;
    }
    return e;
  };
}
export default new GetParent();
