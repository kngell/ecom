module.exports = {
  entry: {
    //Home Main
    "js/main/frontend/main": {
      import: ["js/main/frontend/main.js"],
      dependOn: "js/librairies/frontlib",
    },
    //Home plugins
    "js/plugins/homeplugins": {
      import: ["js/plugins_entries/homeplugins"],
      dependOn: "js/librairies/frontlib",
    },
    //Ecommerce - Index page js
    "js/custom/client/home/home": {
      import: ["js/custom/client/home/index"],
      dependOn: "js/librairies/frontlib",
    },
    //Ecommerce - Validate user account
    "js/custom/client/users/account/validate": {
      import: ["js/custom/client/users/account/validate"],
      dependOn: "js/librairies/frontlib",
    },
  },
};
