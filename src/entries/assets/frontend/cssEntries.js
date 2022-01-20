module.exports = {
  entry: {
    //Front pages Main
    "css/main/frontend/main": {
      import: ["css/main/frontend/main.sass"],
      dependOn: "css/librairies/frontlib",
    },
    //Home plugins
    "css/plugins/homeplugins": {
      import: ["css/plugins_entries/homeplugins.sass"],
      dependOn: "css/librairies/frontlib",
    },
    //Index page css ecommerce
    "css/custom/client/home/home": {
      import: ["css/custom/client/home/index.sass"],
      dependOn: "css/librairies/frontlib",
    },
  },
};
