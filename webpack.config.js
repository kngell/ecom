const path = require("path");
const webpack = require("webpack");
const plugins = require("./webpack.plugins");
const { merge } = require("webpack-merge");
const RemoveEmptyScriptsPlugin = require("webpack-remove-empty-scripts");
const RemovePlugin = require("remove-files-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin");

const {
  alias,
  fontendAssetsConfig,
  adminAssetsConfig,
  viewsConfig,
} = require("./webpack.partials");
const ASSET_PATH =
  process.env.ASSET_PATH ||
  `${path.sep}kngell${path.sep}public${path.sep}assets${path.sep}`;

const commonConfig = merge(plugins, {
  devtool: false,
  resolve: {
    alias: alias,
  },
  stats: {
    errorDetails: true,
    children: true,
  },
});
/**
 * Developpement Config
 * =============================================================
 */
const developmentConfig = {
  plugins: [
    new RemovePlugin({
      after: {
        test: [
          {
            folder: "public/assets/css",
            method: (absoluteItemPath) => {
              return new RegExp(/\.js$/, "m").test(absoluteItemPath);
            },
            recursive: true,
          },
          {
            folder: "public/assets/js",
            method: (absoluteItemPath) => {
              return new RegExp(/\.hot-update.js$/, "m").test(absoluteItemPath);
            },
            recursive: true,
          },
        ],
      },
    }),
    new RemoveEmptyScriptsPlugin({
      verbose: true,
    }),
    new webpack.SourceMapDevToolPlugin({}),
  ],
  optimization: {
    minimize: false,
  },
};

/**
 * Production config
 * ==============================================================
 */
const productionConfig = {
  plugins: [
    new RemoveEmptyScriptsPlugin({ verbose: false }),
    new webpack.SourceMapDevToolPlugin({
      filename: "sourcemaps/[file].map",
      publicPath: ASSET_PATH,
      fileContext: "public",
    }),
    new ImageMinimizerPlugin({
      minimizer: {
        implementation: ImageMinimizerPlugin.imageminMinify,
        options: {
          // Lossless optimization with custom option
          // Feel free to experiment with options for better result for you
          plugins: [
            ["gifsicle", { interlaced: true }],
            ["jpegtran", { progressive: true }],
            ["optipng", { optimizationLevel: 5 }],
            // Svgo configuration here https://github.com/svg/svgo#configuration
            [ ],
          ],
        },
      },
    }),
  ],
  optimization: {
    minimizer: [
      new CssMinimizerPlugin({
        parallel: true,
        minimizerOptions: {
          preset: [
            "default",
            {
              discardComments: { removeAll: true },
            },
          ],
        },
        minify: CssMinimizerPlugin.cssnanoMinify,
      }),
      new TerserPlugin({
        exclude: [path.resolve(__dirname, "node_modules")],
        terserOptions: {
          format: {
            comments: false,
          },
        },
        extractComments: false,
      }),
    ],
    // usedExports: true,
  },
};

module.exports = () => {
  switch (process.env.NODE_ENV) {
    case "development":
      return [
        merge(viewsConfig, commonConfig, developmentConfig),
        merge(fontendAssetsConfig, commonConfig, developmentConfig),
        merge(adminAssetsConfig, commonConfig, developmentConfig),
      ];
    case "production":
      viewsConfig.plugins.push(
        new RemovePlugin({
          before: {
            include: [
              path.join(__dirname, "public", "assets"),
              path.join(__dirname, "app", "views"),
            ],
          },
        })
      );
      return [
        merge(viewsConfig, commonConfig, productionConfig),
        merge(fontendAssetsConfig, commonConfig, productionConfig),
        merge(adminAssetsConfig, commonConfig, productionConfig),
      ];
    default:
      throw new Error("No matching configuration was found!");
  }
};
