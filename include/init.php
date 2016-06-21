<?php
/* *************************************************************
 * Zend framework init - start
 * *************************************************************/
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/class");
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/");
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/pdf");
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/");

require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

Zend_Session::start();
$auth_session = new Zend_Session_Namespace('Zend_Auth');

// start use of zend_cache and set the lifetime for 2 hours.
// $frontendOptions = array('lifetime' => 7200, 'automatic_serialization' => true);
/* *************************************************************
 * Zend framework init - end
 * *************************************************************/

/* *************************************************************
 * Smarty inint - start
 * *************************************************************/
require_once ("smarty/Smarty.class.php");
require_once ("library/paypal/paypal.class.php");

require_once ('./library/HTMLPurifier/HTMLPurifier.standalone.php');
include_once ('./include/functions.php');

if (!is_writable('./tmp')) {
    simpleInvoicesError('notWriteable', 'directory', './tmp');
}

/* *************************************************************
 * log file - start
 * *************************************************************/
$logFile = "./tmp/log/si.log";
if (!is_file($logFile)) {
    $createLogFile = fopen($logFile, 'w') or die(simpleInvoicesError('notWriteable', 'folder', 'tmp/log'));
    fclose($createLogFile);
}
if (!is_writable($logFile)) {
    simpleInvoicesError('notWriteable', 'file', $logFile);
}
$writer = new Zend_Log_Writer_Stream($logFile);
$logger = new Zend_Log($writer);
/* *************************************************************
 * log file - end
 * *************************************************************/

if (!is_writable('./tmp/cache')) {
    simpleInvoicesError('notWriteable', 'file', './tmp/cache');
}

include_once ('./config/define.php');

// Include another config file if required
$config_file_path = "";
if (is_file('./config/custom.config.php')) {
    $config = new Zend_Config_Ini('./config/custom.config.php', $environment, true);
    $config_file_path = "config/custom.config.php";
} else {
    // added 'true' to allow modifications from db
    $config = new Zend_Config_Ini('./config/config.php', $environment, true);
    $config_file_path = "config/config.php";
}

// set up app with relevant php setting
date_default_timezone_set($config->phpSettings->date->timezone);
error_reporting($config->debug->error_reporting);

// @formatter:off
ini_set('display_startup_errors', $config->phpSettings->display_startup_errors);
ini_set('display_errors',         $config->phpSettings->display_errors);
ini_set('log_errors',             $config->phpSettings->log_errors);
ini_set('error_log',              $config->phpSettings->error_log);

$zendDb = Zend_Db::factory($config->database->adapter,
                          array('host'     => $config->database->params->host,
                                'username' => $config->database->params->username,
                                'password' => $config->database->params->password,
                                'dbname'   => $config->database->params->dbname,
                                'port'     => $config->database->params->port));

try {
    $session_timeout = $zendDb->fetchRow("SELECT value FROM ". TB_PREFIX . "system_defaults
                                          WHERE name='session_timeout'");
} catch (Exception $ex) {
    $session_timeout = 60;
}
// @formatter:on
$timeout = intval($session_timeout['value']);
if ($timeout <= 0) {
    error_log("Extension - system Defaults - invalid timeout value[$timeout]");
    $timeout = 60;
}

// start use of zend_cache and set the lifetime for 2 hours.
// $frontendOptions = array('lifetime' => 7200, 'automatic_serialization' => true);
$frontendOptions = array('lifetime' => ($timeout * 60), 'automatic_serialization' => true);

/* *************************************************************
 * Zend Framework cache section - start
 * -- must come after the tmp dir writeable check
 * *************************************************************/
$backendOptions = array('cache_dir' => './tmp/'); // Directory where to put the cache files

// getting a Zend_Cache_Core object
$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

// required for some servers
Zend_Date::setOptions(array('cache' => $cache)); // Active aussi pour Zend_Locale
/* *************************************************************
 * Zend Framework cache section - end
 * *************************************************************/

$smarty = new Smarty();

$smarty->assign("config_file_path", $config_file_path);

$smarty->debugging = false;

// cache directory. Have to be writeable (chmod 777)
$smarty->compile_dir = "tmp/cache";
if (!is_writable($smarty->compile_dir)) {
    simpleInvoicesError("notWriteable", 'folder', $smarty->compile_dir);
}

// adds own smarty plugins
$smarty->plugins_dir = array("plugins", "include/smarty_plugins");

// add stripslash smarty function
$smarty->register_modifier("unescape", "stripslashes");
/* *************************************************************
 * Smarty inint - end
 * *************************************************************/

$path = pathinfo($_SERVER['REQUEST_URI']);
// SC: Install path handling will need changes if used in non-HTML contexts
$install_path = htmlsafe($path['dirname']);

include_once ("./include/class/db.php");
include_once ("./include/class/index.php");
$db = db::getInstance();

include_once ("./include/sql_queries.php");

// Set these global variables. $databaseBuilt says that there is structure
// in the database and $databasePopulated says there is data in the database.
// Not that the si_biller table is used to determine if there is structure
// by the presence of a table that will always exist regardless of version.
$databaseBuilt = checkTableExists(TB_PREFIX . "biller");
if ($databaseBuilt) {
    // This is a global variable as well as a smarty value.
    $patchCount = getNumberOfDoneSQLPatches();
    $databasePopulated = $patchCount > 0;
}
$smarty->assign('patchCount', $patchCount);

// @formatter:off
$smarty->register_modifier("siLocal_number"          , array("siLocal", "number"));
$smarty->register_modifier("siLocal_number_clean"    , array("siLocal", "number_clean"));
$smarty->register_modifier("siLocal_number_trim"     , array("siLocal", "number_trim"));
$smarty->register_modifier("siLocal_number_formatted", array("siLocal", "number_formatted"));
$smarty->register_modifier("siLocal_date"            , array("siLocal", "date"));

$smarty->register_modifier('htmlsafe' , 'htmlsafe');
$smarty->register_modifier('urlsafe'  , 'urlsafe');
$smarty->register_modifier('urlencode', 'urlencode');
$smarty->register_modifier('outhtml'  , 'outhtml');
$smarty->register_modifier('htmlout'  , 'outhtml');   //common typo
$smarty->register_modifier('urlescape', 'urlencode'); //common typo
// @formatter:on

loadSiExtentions();

checkConnection();

$defaults = getSystemDefaults();
$smarty->assign("defaults", $defaults);

include_once ('./include/language.php');

include ('./include/include_auth.php');
include_once ('./include/manageCustomFields.php');
include_once ("./include/validation.php");

if ($databaseBuilt && $config->authentication->enabled == 1) {
    include_once ("./include/acl.php");
    // if authentication enabled then do acl check etc..
    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/include/acl.php")) {
            require_once ("./extensions/$ext_name/include/acl.php");
        }
    }
    include_once ("./include/check_permission.php");
}

/* *************************************************************
 * Array: $early_exit - Add pages that don't need a header or
 * that exit prior to adding the template add in here
 * *************************************************************/
$early_exit = array();
$early_exit[] = "auth_login";
$early_exit[] = "api_cron";
$early_exit[] = "auth_logout";
$early_exit[] = "export_pdf";
$early_exit[] = "export_invoice";
$early_exit[] = "statement_export";
$early_exit[] = "invoice_template";
$early_exit[] = "payments_print";
$early_exit[] = "documentation_view";

switch ($module) {
    case "export":
        $smarty_output = "fetch";
        break;
    default:
        $smarty_output = "display";
        break;
}

// get the url - used for templates / pdf
$siUrl = getURL();

/* *************************************************************
 * If using the folowing line, the DB settings should be
 * appended to the config array, instead of replacing it
 * (NOTE: NOT TESTED!)
 * *************************************************************/
include_once ("./include/backup.lib.php");
