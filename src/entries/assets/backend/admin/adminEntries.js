const cssEntries = require("./cssEntries");
const jsEntries = require("./jsEntries");
const { merge } = require("webpack-merge");
module.exports = merge(jsEntries, cssEntries);
