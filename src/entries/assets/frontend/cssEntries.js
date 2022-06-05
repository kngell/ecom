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
    "css/custom/client/brand/phones/home/home": {
      import: ["css/custom/client/brand/phones/home/index.sass"],
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
    //Email Template main
    "css/custom/client/users/email/main": {
      import: ["css/custom/client/users/email/main.sass"],
      dependOn: "css/librairies/frontlib",
    },
    //Email Verify user account
    "css/custom/client/users/account/verify": {
      import: ["css/custom/client/users/account/verify.sass"],
      dependOn: "css/librairies/frontlib",
    },
    //Email Validate user account
    "css/custom/client/users/account/validate": {
      import: ["css/custom/client/users/account/validate.sass"],
      dependOn: "css/librairies/frontlib",
    },
    // Learning
    "css/custom/client/learn/learn": {
      import: ["css/custom/client/learn/learn.sass"],
      dependOn: "css/librairies/frontlib",
    },
  },
};
