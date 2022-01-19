export default class editorLoader {
  constructor(editors, token) {
    this.editors = editors;
    this.editor = [];
    this.isLoad = false;
    this.token = token;
  }
  check() {
    return this.isLoad == true;
  }

  async load() {
    const p = this;
    const { default: myEditor } = await import(
      /* webpackMode: "lazy" */
      /* webpackChunkName: "editor" */
      "corejs/editor"
    );
    return new Promise((resolve, reject) => {
      $.each(p.editors, async (i, ed) => {
        p.editor[ed] = await new myEditor(ed, p.token)._createEditor();
      });
      p.isLoad = true;
      resolve(p.editor);
    });
  }
}
