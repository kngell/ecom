const path = require("path");
const CopyPlugin = require("copy-webpack-plugin");
const AssetsPlugin = require("assets-webpack-plugin");
const DelWebpackPlugin = require("del-webpack-plugin");
const webpack = require("webpack");
const CKEditorWebpackPlugin = require("@ckeditor/ckeditor5-dev-webpack-plugin");
// const plugins = require("./plugins");

module.exports = {
  plugins: [
    new DelWebpackPlugin({
      include: ["*.js", "*.css", "*vendors-node_modules_ckeditor_*"], //"*.js",
      info: true,
      keepGeneratedAssets: false,
      allowExternal: false,
    }),
    new AssetsPlugin({
      filename: "assets.json",
      includeManifest: "manifest",
      path: path.join(__dirname, "App"),
      processOutput: function (assets) {
        return JSON.stringify(assets);
      },
      includeAllFileTypes: false,
      fileTypes: ["js", "css"],
      integrity: true,
    }),
    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery",
      "window.jQuery": "jquery",
      // "global.jQuery": "jquery",
      Popper: "@popperjs/core",
    }),
    new webpack.ProgressPlugin(),
    // plugins.ESLintPlugin,
    // new CKEditorWebpackPlugin({
    //   language: "pl",
    //   outputDirectory: "assets/js/ckeditor5-translations",
    //   additionalLanguages: ["all"],
    //   addMainLanguageTranslationsToAllAssets: true,
    //   verbose: true,
    // }),
    new CopyPlugin({
      patterns: [
        {
          from: path.join(__dirname, "src", ".htaccess"),
          to: path.resolve(__dirname, "public"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "index.php"),
          to: path.resolve(__dirname, "public"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "users"),
          to: path.resolve(__dirname, "public", "assets", "img", "users"),
          toType: "dir",
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "clothes"),
          to: path.resolve(__dirname, "public", "assets", "img", "clothes"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "shop"),
          to: path.resolve(__dirname, "public", "assets", "img", "shop"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "watches"),
          to: path.resolve(__dirname, "public", "assets", "img", "watches"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "insta"),
          to: path.resolve(__dirname, "public", "assets", "img", "insta"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "brand"),
          to: path.resolve(__dirname, "public", "assets", "img", "brand"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "arrivals"),
          to: path.resolve(__dirname, "public", "assets", "img", "arrivals"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "featured"),
          to: path.resolve(__dirname, "public", "assets", "img", "featured"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "camera"),
          to: path.resolve(__dirname, "public", "assets", "img", "camera"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "products"),
          to: path.resolve(__dirname, "public", "assets", "img", "products"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "sliders"),
          to: path.resolve(__dirname, "public", "assets", "img", "sliders"),
          noErrorOnMissing: true,
        },
        {
          from: path.join(__dirname, "src", "assets", "img", "blog"),
          to: path.resolve(__dirname, "public", "assets", "img", "blog"),
          noErrorOnMissing: true,
        },
        // {
        //   from: path.join(__dirname, "src", "assets", "img", "posts"),
        //   to: path.resolve(__dirname, "public", "assets", "img", "posts"),
        //   noErrorOnMissing: true,
        // },
      ],
    }),
  ],
};
