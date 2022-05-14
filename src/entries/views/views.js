import "views/client/errors/_errors.php";
import "views/client/restricted/index.php";
/**
 * Home
 * ==================================================
 */
// Home Layout
import "views/client/layouts/inc/default/footer.php";
import "views/client/layouts/inc/default/header.php";
import "views/client/layouts/inc/default/nav.php";
import "views/client/layouts/inc/default/modal.php";
import "views/client/layouts/default.php";

//home Pages ecommerce index
import "views/client/home/index.php";
import "views/client/home/partials/_banner_adds.php";
import "views/client/home/partials/_banner_area.php";
import "views/client/home/partials/_blog.php";
import "views/client/home/partials/_empty_cart_template.php";
import "views/client/home/partials/_new_products.php";
import "views/client/home/partials/_top_sales.php";
import "views/client/home/partials/_special_price.php";

//Home Users Account
import "views/client/users/account/login.php";

//Learn
import "views/client/learn/learn.php";

//EmailTemplate
import "views/client/users/emailTemplate/emailTemplate.php";
import "views/client/users/emailTemplate/forgotPasswordEmailTemplate.php";
import "views/client/users/emailTemplate/welcomeTemplate.php";
import "views/client/layouts/inc/EmailTemplate/footer.php";
import "views/client/layouts/inc/EmailTemplate/header.php";
import "views/client/layouts/emailTemplate.php";

//Email AccountValidation
import "views/client/users/account/verifyUserAccount.php";
