<?php

declare(strict_types=1);

final class BaseConstants
{
    /** Path the vendor src directory */
    private static string $vendorPath = 'vendor';
    /* Path the error handler resource files */
    private static string $erorrResource = 'ErrorHandler/Resources/';

    /**
     * Defined common constants which are commonly used throughout the framework.
     *
     * @return void
     */
    public static function load(): void
    {
        defined('PUBLIC_PATH') or define('PUBLIC_PATH', 'public');
        defined('ASSET_PATH') or define('ASSET_PATH', '/' . PUBLIC_PATH . '/assets');
        defined('CSS_PATH') or define('CSS_PATH', ASSET_PATH . '/css');
        defined('JS_PATH') or define('JS_PATH', ASSET_PATH . '/js');
        defined('IMAGE_PATH') or define('IMAGE_PATH', ASSET_PATH . '/images');
        defined('TEMPLATES') or define('TEMPLATES', $_SERVER['DOCUMENT_ROOT'] . 'App/Templates/');
        defined('STORAGE_PATH') or define('STORAGE_PATH', APP_ROOT . DS . 'Storage');
        defined('CACHE_PATH') or define('CACHE_PATH', STORAGE_PATH . DS);
        defined('LOG_PATH') or define('LOG_PATH', STORAGE_PATH . DS . 'logs');
        defined('ROOT_URI') or define('ROOT_URI', '');
        defined('RESOURCES') or define('RESOURCES', ROOT_URI);
        defined('UPLOAD_PATH') or define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . DS . 'uploads/');
        //Kngell
        defined('ERROR_RESOURCE') or define('ERROR_RESOURCE', $_SERVER['DOCUMENT_ROOT'] . self::$vendorPath . self::$erorrResource);
        defined('TEMPLATE_ERROR') or define('TEMPLATE_ERROR', APP_ROOT . DS . self::$vendorPath . self::$erorrResource);
        defined('MAGMACORE') or define('MAGMACORE', $_SERVER['DOCUMENT_ROOT'] . self::$vendorPath);

        defined('ROOT') or define('ROOT', dirname(__DIR__, 3) . DS);
        defined('VENDOR') or define('VENDOR', ROOT . 'vendor' . DS);
        defined('APP') or define('APP', ROOT . 'App' . DS);
        defined('FILES') or define('FILES', ROOT . 'Files' . DS);
        defined('CORE') or define('CORE', ROOT . 'App' . DS . 'Core' . DS);
        defined('MODEL') or define('MODEL', ROOT . 'App' . DS . 'Models' . DS);
        defined('VIEW') or define('VIEW', ROOT . 'App' . DS . 'Views' . DS);
        defined('DATA') or define('DATA', ROOT . 'App' . DS . 'Data' . DS);
        defined('SITEMAP') or define('SITEMAP', ROOT . DS . 'Sitemap' . DS);
        defined('CONTROLLER') or define('CONTROLLER', ROOT . 'App' . DS . 'Http' . DS . 'Controllers' . DS);
        defined('IMAGE_ROOT') or define('IMAGE_ROOT', ROOT . 'public' . DS . 'assets' . DS . 'img' . DS);
        defined('ASSET') or define('ASSET', ROOT . 'public' . DS . 'assets' . DS);
        defined('IMAGE_ROOT_SRC') or define('IMAGE_ROOT_SRC', ROOT . 'src' . DS . 'assets' . DS . 'img' . DS);
        defined('ACME_ROOT') or define('ACME_ROOT', ROOT . 'public' . DS . 'assets' . DS . 'acme-challenge' . DS);
        defined('UPLOAD_ROOT') or define('UPLOAD_ROOT', ROOT . 'public' . DS . 'assets' . DS . 'img' . DS . 'upload' . DS);
        defined('LAZYLOAD_ROOT') or define('LAZYLOAD_ROOT', ROOT . 'public' . DS . 'assets' . DS . 'lazyload' . DS);
        defined('CUSTOM_VALIDATOR') or define('CUSTOM_VALIDATOR', ROOT . DS . 'app' . DS . 'custom_validator' . DS);
        defined('CACHE_DIR') or define('CACHE_DIR', ROOT . 'Storage' . DS . 'Cache' . DS);

        // -----------------------------------------------------------------------
        // URL ROOT
        // -----------------------------------------------------------------------
        defined('HOST') or define('HOST', 'https://localhost:8001');
        defined('URLROOT') or define('URLROOT', HOST . US . 'kngell' . US);
        // -----------------------------------------------------------------------
        // SITE NAME
        // -----------------------------------------------------------------------
        defined('SITE_TITLE') or define('SITE_TITLE', "K'nGELL Ingénierie Logistique");
        // -----------------------------------------------------------------------
        // DEFAULT ITEMS
        // -----------------------------------------------------------------------
        defined('DEFAULT_CONTROLLER') or define('DEFAULT_CONTROLLER', 'Home');
        defined('DEFAULT_METHOD') or define('DEFAULT_METHOD', 'index');

        defined('DEBUG') or define('DEBUG', true);
        defined('DEFAULT_LAYOUT') or define('DEFAULT_LAYOUT', 'default');

        // -----------------------------------------------------------------------
        // SCRIPT/CSS/IMG ACCESS
        // -----------------------------------------------------------------------
        defined('PROOT') or define('PROOT', DS . 'kngell' . DS);
        defined('SCRIPT') or define('SCRIPT', dirname($_SERVER['SCRIPT_NAME']));
        defined('CSS') or define('CSS', SCRIPT . DS . 'assets' . DS . 'css' . DS);
        defined('JS') or define('JS', SCRIPT . DS . 'assets' . DS . 'js' . DS);
        defined('IMG') or define('IMG', SCRIPT . DS . 'assets' . DS . 'img' . DS);
        defined('FONT') or define('FONT', URLROOT . 'public' . DS . 'assets' . DS . 'fonts' . DS);
        defined('ADMIN_CSS') or define('ADMIN_CSS', SCRIPT . DS . 'assets' . DS . 'css' . DS . 'admin' . DS);
        defined('ADMIN_JS') or define('ADMIN_JS', SCRIPT . DS . 'assets' . DS . 'js' . DS . 'admin' . DS);
        defined('ADMIN_IMG') or define('ADMIN_IMG', SCRIPT . DS . 'assets' . DS . 'admin' . DS . 'img' . DS);
        defined('ADMIN_PG') or define('ADMIN_PG', SCRIPT . DS . 'assets' . DS . 'js' . DS . 'admin' . DS . 'plugins' . DS);
        defined('NODE_MODULE') or define('NODE_MODULE', SCRIPT . DS . 'node_modules' . DS);
        defined('CKFINDER') or define('CKFINDER', SCRIPT . DS . 'ckfinder' . DS);
        defined('UPLOAD') or define('UPLOAD', SCRIPT . DS . 'assets' . DS . 'img' . DS . 'upload' . DS);
        defined('LAZYLOAD') or define('LAZYLOAD', SCRIPT . DS . 'assets' . DS . 'lazyload' . DS);

        // -----------------------------------------------------------------------
        // VISITORS, LOGIN & REGISTRATION
        // -----------------------------------------------------------------------
        defined('CURRENT_USER_SESSION_NAME') or define('CURRENT_USER_SESSION_NAME', 'user');
        defined('REMEMBER_ME_COOKIE_NAME') or define('REMEMBER_ME_COOKIE_NAME', 'hash');
        defined('VISITOR_COOKIE_NAME') or define('VISITOR_COOKIE_NAME', 'gcx_kngell_eshop01_visitor');
        defined('USER_COOKIE_NAME') or define('USER_COOKIE_NAME', 'gcx_kngell_eshop01_user');
        defined('COOKIE_EXPIRY') or define('COOKIE_EXPIRY', 60 * 60 * 24 * 360);
        defined('TOKEN_NAME') or define('TOKEN_NAME', 'token');
        defined('SERIAL') or define('SERIAL', 'serialx21589874');
        defined('SALT') or define('SALT', 'xslsaltiduser');
        defined('REDIRECT') or define('REDIRECT', 'page_to_redirect');
        defined('SMTP_HOST') or define('SMTP_HOST', 'mail.kngell.com');
        defined('SMTP_PORT') or define('SMTP_PORT', 465);
        defined('SMTP_USERNAME') or define('SMTP_USERNAME', 'admin@kngell.com');
        defined('SMTP_PASSWORD') or define('SMTP_PASSWORD', 'Akonoakono169&169');
        defined('SMTP_FROM') or define('SMTP_FROM', 'admin@kngell.com');
        defined('SMTP_FROM_NAME') or define('SMTP_FROM_NAME', 'K\'nGELL Consulting & Services');
        defined('MAX_LOGIN_ATTEMPTS_PER_HOUR') or define('MAX_LOGIN_ATTEMPTS_PER_HOUR', 5);
        defined('MAX_IMG_SIZE') or define('MAX_IMG_SIZE', 5 * 1024 * 1024);
        defined('MAX_EMAIL_VERIFICATION_PER_DAY') or define('MAX_EMAIL_VERIFICATION_PER_DAY', 3);
        defined('PASSWORD_RESET_REQUEST_EXPIRY_TIME') or define('PASSWORD_RESET_REQUEST_EXPIRY_TIME', 60 * 60);
        defined('MAX_PW_RESET_REQUESTS_PER_DAY') or define('MAX_PW_RESET_REQUESTS_PER_DAY', 3);
        // -----------------------------------------------------------------------
        // EMAILS
        // -----------------------------------------------------------------------
        defined('EMAIL_FROM') or define('EMAIL_FROM', 'no-reply@kngell.com');
        defined('MAIL_ENABLED') or define('MAIL_ENABLED', true);

        // -----------------------------------------------------------------------
        // PERMISSIONS
        // -----------------------------------------------------------------------
        defined('ACCESS_RESTRICTED') or define('ACCESS_RESTRICTED', 'Restricted');

        // -----------------------------------------------------------------------
        // FACEBOOK
        // -----------------------------------------------------------------------
        defined('FB_APP_ID') or define('FB_APP_ID', '297739978156061');
        defined('FB_APP_SECRET') or define('FB_APP_SECRET', 'a4ff4070fc4261a36d9ff551ec7cd07f');
        defined('FB_LOGIN_URL') or define('FB_LOGIN_URL', 'https://localhost/kngell/guests/fblogin');
        defined('FB_GRAPH_VERSION') or define('FB_GRAPH_VERSION', 'v6.0');
        defined('FB_GRAPH_DOMAIN') or define('FB_GRAPH_DOMAIN', 'https://graph.facebook.com/');
        defined('FB_GRAPH_STATE') or define('FB_GRAPH_STATE', 'eciphp');
        // -----------------------------------------------------------------------
        // Keys
        // -----------------------------------------------------------------------
        defined('IP_KEY') or define('IP_KEY', '4eb97a89cdfdaf7a911e1c0a9b01dc78b72f85d8fe297572e7fb549d9d3a0c33');
        defined('EMAIL_KEY') or define('EMAIL_KEY', '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');

        // -----------------------------------------------------------------------
        // PAYPAL
        // -----------------------------------------------------------------------
        defined('PAYPAL_CLIENT_ID') or define('PAYPAL_CLIENT_ID', '');
        defined('PAYPAL_SECRET_ID') or define('PAYPAL_SECRET_ID', '');
        defined('PAYPAL_SANDBOX') or define('PAYPAL_SANDBOX', true);
        // -----------------------------------------------------------------------
        // CHECKOUT
        // -----------------------------------------------------------------------
        defined('CHECKOUT_PROCESS_NAME') or define('CHECKOUT_PROCESS_NAME', 'checkoutxxxxkljdfd');
        defined('TRANSACTION_ID') or define('TRANSACTION_ID', 'trsss_checkout_transaction');
        defined('BRAND_NUM') or define('BRAND_NUM', 'checkoutxxxxkljdfd');
        // -----------------------------------------------------------------------
        // Time zone cookies
        // -----------------------------------------------------------------------
        date_default_timezone_set('UTC');
        // session_set_cookie_params(['samesite' => 'Strict']);
        // -----------------------------------------------------------------------
        // Form
        // -----------------------------------------------------------------------
        defined('CSRF_TOKEN_SECRET') or define('CSRF_TOKEN_SECRET', 'sdgdsfdsffgfgglkglqhgfjgqe46454878');
        // -----------------------------------------------------------------------
        // Comments
        // -----------------------------------------------------------------------
        defined('COMMENT_APPROVAL_REQUIRED') or define('COMMENT_APPROVAL_REQUIRED', 0);
    }

    /**
     * Return the vendor directory source path.
     *
     * @return string
     */
    public static function getVendorSrcDir(): string
    {
        return self::$vendorPath;
    }
}