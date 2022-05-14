const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const devMode = process.env.NODE_ENV !== "production";
const config = require("./config");
const ASSET_PATH = config.ROOT + config.PATH;
//const ASSET_PATH = process.env.ASSET_PATH || config.ROOT_PATH; //`${path.sep}public${path.sep}assets${path.sep}`;

exports.viewRules = {
  mode: "development",
  module: {
    rules: [
      {
        test: /\.php$/i,
        type: "asset/resource",
      },
      {
        test: /\.php$/i,
        use: [
          "extract-loader",
          {
            loader: "html-loader",
            options: {
              esModule: false,
            },
          },
        ],
      },
      {
        test: /\.(png|svg|jpg|gif|ico)$/i,
        exclude: [
          /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
          /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css/,
        ],
        type: "javascript/auto",
        use: [
          {
            loader: "file-loader",
            options: {
              name: "[name].[ext]",
              outputPath: "../../public/assets/img",
              publicPath: (url) => {
                return ASSET_PATH + "img/" + url;
              },
            },
          },
        ],
      },
    ],
  },
};

exports.assetsRuless = {
  module: {
    generator: {
      "asset/resource": {
        publicPath: "https://localhost/public/assets/",
      },
    },
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
        },
      },
      {
        test: /\.css$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              publicPath: "/",
            },
          },
          {
            loader: "css-loader",
          },
          {
            loader: "postcss-loader",
          },
        ],
      },
      {
        test: /\.s[ac]ss$/i,
        // exclude: /(EmailTemplate)/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              publicPath: "./",
            },
          },
          {
            loader: "css-loader",
          },
          {
            loader: "postcss-loader",
          },
          {
            loader: "sass-loader",
          },
        ],
      },
      // {
      //   test: /\.s[ac]ss$/i,
      //   include: path.resolve(
      //     __dirname,
      //     "src",
      //     "assets",
      //     "css",
      //     "lib",
      //     "EmailTemplate",
      //     "emailTemplate.sass"
      //   ),
      //   use: ["style-loader", "css-loader", "postcss-loader", "sass-loader"],
      // },

      {
        test: /\.(png|svg|jpg|gif|ico)$/i,
        exclude: [
          /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
          /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css/,
        ],
        type: "asset/resource",
        generator: {
          filename: devMode
            ? "img/[name][ext][query]"
            : "img/[name][hash][ext][query]",
        },
      },
      {
        test: /.(ttf|otf|eot|woff(2)?)(\?[a-z0-9]+)?$/i,
        type: "asset/resource",
        generator: {
          filename: devMode
            ? "fonts/[name][ext][query]"
            : "fonts/[name][hash][ext][query]",
        },
      },
      {
        test: /\.svg$/,
        type: "javascript/auto",
        include: [
          /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
          /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css/,
        ],
        use: [
          {
            loader: "raw-loader",
            options: {},
          },
        ],
      },
      // {
      //   test: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css$/,
      //   use: [
      //     MiniCssExtractPlugin.loader,
      //     "css-loader",
      //     {
      //       loader: "postcss-loader",
      //     },
      //   ],
      // },
    ],
  },
};
