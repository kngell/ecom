const path = require("path");
module.exports = {
  PATH:
    process.env.ASSET_PATH || `${path.sep}public${path.sep}assets${path.sep}`,
  ROOT: "https://localhost:8001",
};
