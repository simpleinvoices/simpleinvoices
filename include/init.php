<?php
/* *************************************************************
 * Zend framework init - start
 * *************************************************************/
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/");
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/pdf");
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/pdf/fpdf");
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/");
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/class");

require_once ("smarty/libs/Smarty.class.php");
require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

Zend_Session::start();
$auth_session = new Zend_Session_Namespace('Zend_Auth');
if ($auth_session) {} // Show variable as used.

// start use of zend_cache and set the lifetime for 2 hours.
// $frontendOptions = array('lifetime' => 7200, 'automatic_serialization' => true);
/* *************************************************************
 * Zend framework init - end
 * *************************************************************/

/* *************************************************************
 * Smarty init - start
 * *************************************************************/
require_once ("library/paypal/paypal.class.php");
require_once ('library/HTMLPurifier/HTMLPurifier.standalone.php');
include_once ('include/functions.php');

if (!is_writable('./tmp')) {
    simpleInvoicesError('notWriteable', 'directory', './tmp');
}

/* *************************************************************
 * log file - start
 * *************************************************************/
$logFile = "tmp/log/si.log";
if (!is_file($logFile)) {
    $createLogFile = fopen($logFile, 'w') or die(simpleInvoicesError('notWriteable', 'folder', 'tmp/log'));
    fclose($createLogFile);
}
if (!is_writable($logFile)) {
    simpleInvoicesError('notWriteable', 'file', $logFile);
}
$writer = new Zend_Log_Writer_Stream($logFile);
$logger = new Zend_Log($writer);
if ($logger) {} // Show variable as used.
/* *************************************************************
 * log file - end
 * *************************************************************/

if (!is_writable('tmp/cache')) {
    simpleInvoicesError('notWriteable', 'file', './tmp/cache');
}

include_once ('config/define.php');
global $environment;

// added 'true' to allow modifications from db
$config = new Zend_Config_Ini("./" . CONFIG_FILE_PATH, $environment, true);
try {
    $dbInfo = new DbInfo(CONFIG_FILE_PATH, "production");
} catch (PdoDbException $pde) {
    if (preg_match('/.*{dbname|password|username}/', $pde->getMessage())) {
        echo "<h1 style='font-weigth:bold;color:red;'>";
        echo "  Update database information in config/config.php";
        echo "</h1>";
    } else {
        echo "<h1 style='font-weigth:bold;color:red;'>";
        echo "  " . $pde->getMessage() . " (Error code: {$pde->getCode})";
        echo "</h1>";
    }
    exit();
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
                           array('host'     => $dbInfo->getHost(),
                                 'username' => $dbInfo->getUsername(),
                                 'password' => $dbInfo->getPassword(),
                                 'dbname'   => $dbInfo->getDbname(),
                                 'port'     => $dbInfo->getPort()));

// It's possible that we are in the initial install mode. If so, set
// a flag so we won't terminate on an "Unknown database" error later.
try {
    $tbl_info = $zendDb->describeTable(TB_PREFIX . "biller");
    $databaseBuilt = !empty($tbl_info);
} catch (Zend_Db_Exception $zde) {
    $databaseBuilt = false;
}

// If session_timeout is defined in the database, use it. If not
// set it to the 60-minute default.
$session_timeout = 60; // default
if ($databaseBuilt) {
    try {
        $timeout = $zendDb->fetchRow("SELECT value FROM ". TB_PREFIX . "system_defaults
                                      WHERE name='session_timeout'");
        $session_timeout = intval($timeout['value']);
    } catch (Zend_Db_Exception $zde) {
        $session_timeout = 0;
    }
}
if ($session_timeout <= 0) $session_timeout = 60;
$frontendOptions = array('lifetime' => ($session_timeout * 60), 'automatic_serialization' => true);
// @formatter:on

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
$smarty->assign("config_file_path", CONFIG_FILE_PATH);

$smarty->debugging = false;
$smarty->setConfigDir("config")
       ->setTemplateDir("templates")
       ->setCompileDir("tmp/template_c")
       ->setCacheDir("tmp/cache")
       ->setPluginsDir(array("library/smarty/libs/plugins", "include/smarty_plugins"));

if (!is_writable($smarty->compile_dir)) {
    simpleInvoicesError("notWriteable", 'folder', $smarty->compile_dir);
}

// add stripslash smarty function
$smarty->registerPlugin('modifier', "unescape", "stripslashes");
//$smarty->testInstall();
/* *************************************************************
 * Smarty init - end
 * *************************************************************/
$path = pathinfo($_SERVER['REQUEST_URI']);
// SC: Install path handling will need changes if used in non-HTML contexts
$install_path = htmlsafe($path['dirname']);
if ($install_path) {} // Show variable as used.

include_once ("include/class/db.php");
// With the database built, a connection should be able to be made
// if the configuration user, password, etc. are set correctly.
$db = ($databaseBuilt ? db::getInstance() : NULL);
if ($db) {} // Show variable as used.

include_once ("include/class/Index.php");
include_once ("include/sql_queries.php");

$patchCount = 0;
if ($databaseBuilt) {
    // Set these global variables.
    $patchCount = getNumberOfDoneSQLPatches();
    $databasePopulated = $patchCount > 0;
}

// Turn authorization off until database is built. It messes up the
// install screens.
if ((!$databaseBuilt || !$databasePopulated) && $config->authentication->enabled == 1) {
    $config->authentication->enabled = 0;
    $module="";
}

$smarty->assign('patchCount', $patchCount);

// @formatter:off
$smarty->registerPlugin('modifier', "siLocal_number"          , array("siLocal", "number"));
$smarty->registerPlugin('modifier', "siLocal_number_clean"    , array("siLocal", "number_clean"));
$smarty->registerPlugin('modifier', "siLocal_number_trim"     , array("siLocal", "number_trim"));
$smarty->registerPlugin('modifier', "siLocal_number_formatted", array("siLocal", "number_formatted"));
$smarty->registerPlugin('modifier', "siLocal_date"            , array("siLocal", "date"));

$smarty->registerPlugin('modifier', 'htmlout'     , 'outhtml');
$smarty->registerPlugin('modifier', 'htmlsafe'    , 'htmlsafe');
$smarty->registerPlugin('modifier', 'outhtml'     , 'outhtml');
$smarty->registerPlugin('modifier', 'urlencode'   , 'urlencode');
$smarty->registerPlugin('modifier', 'urlescape'   , 'urlencode');
$smarty->registerPlugin('modifier', 'urlsafe'     , 'urlsafe');

// @formatter:on

global $ext_names;
loadSiExtentions($ext_names);

// point to extension plugin directories if present.
$plugin_dirs = array();
foreach ($ext_names as $ext_name) {
    $dir_tmp = "extensions/$ext_name/include/smarty_plugins";
    if (is_dir($dir_tmp)) {
        $plugin_dirs[] = $dir_tmp;
    }
}

if (!empty($plugin_dirs)) {
    $smarty->addPluginsDir($plugin_dirs);
}

$defaults = getSystemDefaults();
$smarty->assign("defaults", $defaults);

include_once ('include/language.php');

include ('include/include_auth.php');
include_once ('include/manageCustomFields.php');
include_once ("include/validation.php");
if ($databaseBuilt && $databasePopulated && $config->authentication->enabled == ENABLED) {
    include_once ("include/acl.php");
    // if authentication enabled then do acl check etc..
    foreach ($ext_names as $ext_name) {
        if (file_exists("extensions/$ext_name/include/acl.php")) {
            require_once ("extensions/$ext_name/include/acl.php");
        }
    }
    include_once ("include/check_permission.php");
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
if ($early_exit) {} // Show variable as used.

switch ($module) {
    case "export":
        $smarty_output = "fetch";
        break;
    default:
        $smarty_output = "display";
        break;
}
if ($smarty_output) {} // Show variable as used.

// get the url - used for templates / pdf
$siUrl = getURL();
if ($siUrl) {} // Show variable as used.

/* *************************************************************
 * If using the folowing line, the DB settings should be
 * appended to the config array, instead of replacing it
 * (NOTE: NOT TESTED!)
 * *************************************************************/
include_once ("include/BackupDb.php");

