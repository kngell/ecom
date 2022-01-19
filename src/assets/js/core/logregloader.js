export default class Logreg {
  constructor(editors = {}) {
    this.isLoad = false;
    this.editors = editors;
  }
  check() {
    return this;
  }
  login = async () => {
    const login = await import(
      /* webpackMode: "lazy" */
      /* webpackChunkName: "logAndReg" */
      "./login_register.class"
    );
    this.isLoad = true;
  };
  // async load() {
  // $.each(this.editors, async (i, ed) => {
  //   this.editor[ed] = await createEditor(ed);
  // });
  // }
}
