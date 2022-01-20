const cssEntries = require("./cssEntries");
const jsEntries = require("./jsEntries");
module.exports = {
  membersCssEntries: function () {
    return cssEntries;
  },
  membersJsEntries: function () {
    return jsEntries;
  },
};
