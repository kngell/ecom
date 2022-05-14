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
    //Email Template Css
    "css/custom/client/users/email/emailTest/emailTemplate": {
      import: ["css/custom/client/users/email/emailTest/emailTemplate.sass"],
      dependOn: "css/librairies/frontlib",
    },
    //Email Forgot Password Template Css
    "css/custom/client/users/email/forgotPassword/forgotPawwordTemplate": {
      import: [
        "css/custom/client/users/email/forgotPassword/forgotPawwordTemplate.sass",
      ],
      dependOn: "css/librairies/frontlib",
    },
    //Email Template welcome
    "css/custom/client/users/email/welcome/welcomeTemplate": {
      import: ["css/custom/client/users/email/welcome/welcomeTemplate.sass"],
      dependOn: "css/librairies/frontlib",
    },
    //Email Validate user account
    "css/custom/client/users/account/validate": {
      import: ["css/custom/client/users/account/validate.sass"],
      dependOn: "css/librairies/frontlib",
    },
  },
};
