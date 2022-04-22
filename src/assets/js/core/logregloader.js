export default class Logreg {
  constructor(editors = {}) {
    this.isLoad = false;
    this.editors = editors;
  }
  check = () => {
    return this;
  };
  login = async () => {
    const login = await import(
      /* webpackMode: "lazy" */
      /* webpackChunkName: "logAndReg" */
      "./login_register.class"
    );
    this.isLoad = true;
  };
  isLoadStatus = (status) => {
    this.isLoad = status;
  };
}
