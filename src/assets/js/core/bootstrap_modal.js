import { Modal } from "bootstrap";
// Bootstrap modal
class Bs_Modal {
  constructor(modals) {
    this.modals = modals;
  }
  _init = () => {
    const p = this;
    return new Promise((resolve, reject) => {
      p.modals.forEach((modal, i) => {
        let my_modal = [];
        my_modal[i] = Modal.getOrCreateInstance(
          document.getElementById(String(modal)),
          {
            keyboard: false,
          }
        );
        resolve(my_modal);
      });
    });
  };
}
export default Bs_Modal;
