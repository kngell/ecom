const path = require("path");
const { merge } = require("webpack-merge");
const devMode = process.env.NODE_ENV !== "production";
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
// const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const frontendEntries = require("./src/entries/assets/frontend/frontendEntries");
const adminEntries = require("./src/entries/assets/backend/admin/adminEntries");
const { viewRules, assetsRuless } = require("./webpack.modules");
const ASSET_PATH =
  process.env.ASSET_PATH || `${path.sep}public${path.sep}assets${path.sep}`;

/**
 * Alias
 * ========================================================================================
 */
exports.alias = {
  moment: path.resolve(__dirname, "node_modules", "moment", "moment"),
  mainjs: path.resolve(__dirname, "public", "assets", "js", "main"),
  corejs: path.resolve(__dirname, "src", "assets", "js", "core"),
  corecss: path.resolve(__dirname, "src", "assets", "css", "core"),
  img: path.resolve(__dirname, "src", "assets", "img"),
  fonts: path.resolve(__dirname, "src", "assets", "fonts"),
  plugins: path.resolve(__dirname, "src", "assets", "plugins"),
  views: path.resolve(__dirname, "src", "views"),
  index: path.resolve(__dirname, "src"),
  entries: path.resolve(__dirname, "src", "entries"),
  js: path.resolve(__dirname, "src", "assets", "js"),
  css: path.resolve(__dirname, "src", "assets", "css"),
};

/**
 * Server Options
 * ========================================================================================
 */
const serverOpt = {
  static: ["./"],
  client: {
    webSocketURL: "auto://myapp.test:80/ws",
  },
  // open: {
  //   app: {
  //     name: "Chrome",
  //   },
  // },
  compress: true,
  host: "localhost",
  port: 8001,
  server: {
    type: "https",
    options: {
      key: "/mnt/d/ssl/local/ssl/localhost.key", //"D:/ssl/local/ssl/localhost.key", //"/mnt/d/ssl/local/localhost.key"
      cert: "/mnt/d/ssl/local/ssl/localhost.crt", //"D:/ssl/local/ssl/localhost.crt", // "/mnt/d/ssl/local/localhost.crt"
    },
  },
  proxy: {
    context: () => true,
    "/**": {
      target: "https://localhost",
      secure: false,
      changeOrigin: true,
      pathRewrite: { "^/ecom": "" },
      // onProxyReq: (proxyReq) => {
      //   if (proxyReq.getHeader("origin")) {
      //     proxyReq.setHeader("origin", "https://localhost");
      //   }
      // },
      // onProxyRes: (response) => {
      //   response.headers["access-control-allow-origin"] =
      //     "https://localhost:8001";
      // },
      // headers: {
      //   "Access-Control-Allow-Origin": "https://localhost:8001",
      // },
    },
  },
  devMiddleware: {
    writeToDisk: (filePath) => {
      return /^(?!.*(hot)).*/.test(filePath);
    },
  },
  client: {
    logging: "none",
  },
  // headers: {
  //   "Access-Control-Allow-Origin": "*",
  //   "Access-Control-Allow-Headers": "*",
  //   "Access-Control-Allow-Methods": "*",
  // },
};

const assetParams = {
  output: {
    path: path.resolve(__dirname, "public", "assets"),
    chunkFilename: devMode
      ? "lazyload/js/home/[name].js"
      : "lazyload/js/home/[name]_[chunkhash].js",
    filename: devMode ? "[name].js" : "[name].[contenthash].js",
    // assetModuleFilename: devMode
    //   ? "ressources/[name][ext][query]"
    //   : "[name].[contenthash].js",
    publicPath: ASSET_PATH,
    library: "kngell",
    libraryTarget: "umd",
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: devMode ? "[name].css" : "[name].[contenthash].css",
      chunkFilename: devMode
        ? "lazyload/css/home/[name].css"
        : "lazyload/css/home/[name]_[chunkhash].css",
    }),
  ],
};
/**
 * FrontEnd Assets
 * ========================================================================================
 */
exports.fontendAssetsConfig = merge(
  frontendEntries,
  assetParams,
  {
    entry: {
      "css/librairies/frontlib": "./src/assets/css/lib/frontlib.sass",
      "js/librairies/frontlib": "./src/assets/js/lib/frontlib",
    },
    optimization: {
      removeEmptyChunks: true,
      splitChunks: {
        cacheGroups: {
          homeCommonVendor: {
            test: /[\\/]node_modules[\\/]((?!@ckeditor).*)[\\/]/, //except ckeditor5
            name: "commons/frontend/commonVendor",
            chunks: "initial",
            minSize: 20000,
            priority: -10,
            minChunks: 2,
            reuseExistingChunk: true,
          },
          homeCustomModules: {
            test: /[\\/]((client).*)|((core).*)[\\/]/,
            name: "commons/frontend/commonCustomModules",
            chunks: "initial",
            minSize: 10000,
            minChunks: 2,
            priority: -20,
            reuseExistingChunk: true,
          },
        },
      },
    },
    devServer: serverOpt,
  },
  assetsRuless
);

/**
 * Backend Assets -- Admin
 * ========================================================================================
 */
exports.adminAssetsConfig = merge(
  adminEntries,
  assetParams,
  {
    entry: {},
    // externals: {
    //   moment: "moment",
    // },
    optimization: {
      splitChunks: {
        cacheGroups: {
          adminCommonVendor: {
            test: /[\\/]node_modules[\\/]((?!@ckeditor).*)[\\/]/, //except ckeditor5
            name: "commons/backend/admin/commonVendor",
            chunks: "initial",
            minSize: 10000,
            priority: -10,
            minChunks: 2,
            reuseExistingChunk: true,
          },
          adminCustomModules: {
            test: /[\\/]((admin).*)|((core).*)|((plugins).*)[\\/]/,
            name: "commons/backend/admin/commonCustomModules",
            chunks: "initial",
            minSize: 10000,
            minChunks: 2,
            priority: -20,
            reuseExistingChunk: true,
          },
          styles: {
            test: /[\\/]((node_modules).*)|((plugins).*)[\\/]((?!@ckeditor).*)[\\/]/,
            name: "commons/backend/admin/commoncss",
            type: "css/mini-extract",
            chunks: "initial",
            minSize: 10000,
            minChunks: 2,
            reuseExistingChunk: true,
          },
        },
      },
    },
  },
  assetsRuless
);

/**
 * Views cinfig
 * ========================================================================================
 */
exports.viewsConfig = merge(
  {
    entry: "entries/views/views",
    output: {
      path: path.resolve(__dirname, "App", "Views"),
      assetModuleFilename: (pathData) => {
        const filepath = path
          .dirname(pathData.filename)
          .split("/")
          .slice(2)
          .join("/");
        return `${filepath}/[name][ext]`;
      },
    },
    plugins: [],
  },
  viewRules
);
/**
 * Development config
 * ========================================================================================
 */
