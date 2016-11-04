<?php

/**
 * @copyright 2010  Exood.com
 */
/** Directory settings goes here */
define('WEBSITE', $_SERVER['SERVER_NAME']);// . ($_SERVER['REMOTE_PORT'] == '80' ? '' : ':' . $_SERVER['REMOTE_PORT'])
define('DS', '/'); //DIRECTORY_SEPARATOR
define('ROOT', dirname(dirname(__FILE__)));
define('FUNCTIONS_DIR', ROOT . DS . 'scripts' . DS . 'functions' . DS);
define('LANGUAGE_DIR', ROOT . DS . 'scripts' . DS . 'lang' . DS);
define('COYPIX_DIR', DS . 'admin' . DS . 'companies' . DS . 'pictures' . DS);
define('STAFFPIX_DIR', DS . 'personnel' . DS . 'employees' . DS . 'pictures' . DS);
define('CLIENTPIX_DIR', DS . 'clients' . DS . 'pictures' . DS);
define('PARENT_PIX_DIR', DS . 'acad' . DS . 'students' . DS . 'parents' . DS . 'pictures' . DS);
define('STUDPIX_DIR', DS . 'acad' . DS . 'students' . DS . 'pictures' . DS);
define('ASSESS_DIR', DS . 'acad' . DS . 'courses' . DS . 'assess' . DS . 'archive' . DS);
define('ASSETPIX_DIR', DS . 'assets' . DS . 'pictures' . DS);
define('PRODPIX_DIR', DS . 'stock' . DS . 'products' . DS . 'pictures' . DS);
define('CFG_DIR', ROOT . DS . 'admin' . DS . 'gateway' . DS . 'config' . DS);
define('DOC_LIB_DIR', ROOT . DS . 'documents' . DS . 'libs' . DS);
define('DOC_ARCHV', ROOT . DS . 'documents' . DS . 'archive' . DS . 'shelf' . DS);
define('EDMS_TMPL_DIR', DOC_ARCHV . 'EDMS' . DS . 'templates' . DS);
define('EDMS_DIR', DOC_ARCHV . 'EDMS' . DS . 'docs' . DS);
define('ERRORS', ROOT . DS . 'logs' . DS . 'error.log');
define('LIB', ROOT . DS . 'lib' . DS);
define('HTML2PDF', 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe');
define('FOXIT', 'C:\Program Files (x86)\Foxit Software\Foxit Reader\FoxitReader.exe');
define('PRINT_SERVER', 'localprinter');

/**
 * Database connection goes here 
 */
define('DB_USER', 'xmanager'); // mysql database username
define('DB_PASS', 'agsltblatwl'); // LG2U4nDrzwv8LPn7 mysql database password
define('DB_HOST', 'localhost'); // database server address, it could be an ip e.g 192.168.0.1
define('MYSQL_DIR', 'D:\xampp\mysql'); // MySQL Path
define('MYSQL_BIN', 'D:\xampp\mysql\bin'); // MySQL Bin dir
/* * if (ROOT == "D:\wamp\www\Manager") {
  } elseif (ROOT == "/var/www/html") {
  define('DB_USER', 'xmanager'); // mysql database username
  define('DB_PASS', '123456'); // LG2U4nDrzwv8LPn7 mysql database password
  define('DB_HOST', '192.168.0.1'); // database server address, it could be an ip e.g 192.168.0.1
  } else {
  define('DB_USER', 'exood_school'); // mysql database username
  define('DB_PASS', 'Demo#123'); // LG2U4nDrzwv8LPn7 mysql database password
  define('DB_HOST', 'mysqlv106'); // database server address, it could be an ip e.g 192.168.0.1
  } */
define('DB_NAME', 'exood_biz'); // database name
define('DB_COY', 'exood_coy'); // database name
define('DB_DOC', 'exood_doc'); // database name
define('DB_TYPE', 'mysql'); // Sat to mysql at default

/**
 * Time Zone 
 */
date_default_timezone_set('Africa/Lagos');

/**
 * SMTP Mail Server Settings
 */
define('EMAIL_USER', 'postman@exood.com'); // Email Address
define('EMAIL_PASS', 'tcrphltfaalvc'); // Email Password
define('SMTP_SERVER', 'mail.exood.com'); // SMTP server
define('SMTP_PORT', '25'); // SMTP Port
define('SMTP_AUTH', true); // Use Authentication

/* RELOAD_SESSION (If we are to delete old session data) 
 */
define('RELOAD_SESSION', false);

/**
 * Names 
 */
define('COY', "Company");
define('X_LOCAL_HOST', 0);
define('CAPTCHA_LEN', 6);
define('BOT_CNT', 4);
define('USE_CAPTCHA', 1);
define('X_SECURE_PASS', 1);

//others
$vendor_sql = vendorFlds("vendors", "VendorName");

function vendorFlds($tab, $fld) {
    $vnd_nam = "CONCAT(IF(`%1\$s`.`ClientType`=2, `%1\$s`.`CompanyName`, 
                CONCAT_WS(' ', `%1\$s`.`ContactLastName`, `%1\$s`.`ContactMidName`, `%1\$s`.`ContactFirstName`, 
                IF(LENGTH(`%1\$s`.`ContactTitle`)>0, CONCAT('(',`%1\$s`.`ContactTitle`,')'), ''))))";
//,' {',`%1\$s`.`VendorID`,'}'
    return sprintf($vnd_nam, $tab) . (strlen($fld) > 0 ? " AS $fld" : "");
}

/* Admin Settings 
 */
define('ADMIN_MAIL', 'admin@exood.com');

session_start();

require_once FUNCTIONS_DIR . 'functions.php';
require_once FUNCTIONS_DIR . 'DB_functions.php';
require_once FUNCTIONS_DIR . 'image.php';
require_once FUNCTIONS_DIR . 'user_methods.php';
require_once FUNCTIONS_DIR . 'http_functions.php';
require_once FUNCTIONS_DIR . 'manage_functions.php';

# Database Connection
$dbh = new mysqli(DB_HOST, DB_USER, DB_PASS);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$buttons = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
removeMagicQuotes();
setReporting(false);
$errors = array();
$xMessages = array();
$xvarR = "";
$_SESSION['pixrnd'] = '?e=' . time();
require_once 'post_init.php';
