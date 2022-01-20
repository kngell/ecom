// const autoprefixer = require("autoprefixer");
const { styles } = require("@ckeditor/ckeditor5-dev-utils");
const ckeditorConfig = styles.getPostCssConfig({
  themeImporter: {
    themePath: require.resolve("@ckeditor/ckeditor5-theme-lark"),
  },
  // minify: true,
});
const normalConfig = {
  parser: "postcss-scss",
  plugins: [
    "postcss-flexbugs-fixes",
    "postcss-grid-kiss",
    [
      "postcss-preset-env",
      {
        autoprefixer: {
          grid: false,
          flexbox: "no-2009",
        },
        stage: 3,
        features: {
          "custom-properties": false,
        },
      },
    ],
  ],
};
module.exports = ({ file }) => {
  if (/ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css$/.test(file)) {
    return ckeditorConfig;
  }
  return normalConfig;
};

// module.exports = ({ file }) =>
//   /@ckeditor/.test(file) ? ckeditorConfig : normalConfig;

// require("postcss-flexbugs-fixes"),
// autoprefixer({ grid: "autoplace" }),
// postcssPresetEnv({
//   stage: 0,
//   features: {
//     "nesting-rules": true,
//     "custom-properties": false,
//   },
// }),

// const autoprefixer = require("autoprefixer");
// const postcssPresetEnv = require("postcss-preset-env");
