const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const devMode = process.env.NODE_ENV !== "production";

exports.viewRules = {
  mode: "development",
  module: {
    rules: [
      {
        test: /\.php$/,
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
                return "/kngell/public/assets/img/" + url;
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
        publicPath: "https://localhost/kngell/public/assets/",
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
