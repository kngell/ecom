export default class TableRezise {
  /**
   *
   * @param {string} tableID
   */
  constructor(tableID) {
    this.tableID = tableID;
  }
  _resize = () => {
    let plugin = this;
    var tbl = document.getElementById(plugin.tableID),
      biggestRow = 0,
      rowHeight = 0,
      row = 0;
    for (row = 0; row < tbl.rows.length; row++) {
      //find biggest row height
      rowHeight = parseInt(tbl.rows[row].offsetHeight);
      if (rowHeight > biggestRow) {
        biggestRow = rowHeight;
      }
    }
    for (row = 0; row < tbl.rows.length; row++) {
      //set all rows to biggest row height
      tbl.rows[row].style.height = biggestRow + "px";
    }
  };
}
