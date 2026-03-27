<?php
/* 
 * Zend framework init - start
 */
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/class");
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/"); // Still needed for custom libraries (paypal, WebApp, etc.)
// PDF library path no longer needed - using Composer autoloader
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/");

// Load Composer autoloader for all managed libraries
require_once('./vendor/autoload.php');

//session_start();
Zend_Session::start();
$auth_session = new Zend_Session_Namespace('Zend_Auth');


//start use of zend_cache   
$frontendOptions = array(
    'lifetime' => 7200, // cache lifetime of 2 hours
    'automatic_serialization' => true
);
                   

/* 
 * Zend framework init - end
 */



#ini_set('display_errors',true);

// Legacy library that's not available via Composer
require_once("library/paypal/paypal.class.php");
include_once('./include/functions.php');

//ob_start('addCSRFProtection');

if (!is_writable('./tmp')) {
    
   simpleInvoicesError('notWriteable','directory','./tmp');
}

/*
 * log file - start
 */
$logFile = "./tmp/log/si.log";
if (!is_file($logFile))
{
	$createLogFile = fopen($logFile, 'w') or die(simpleInvoicesError('notWriteable','folder','tmp/log'));
	fclose($createLogFile);
}
if (!is_writable($logFile)) {
	
   simpleInvoicesError('notWriteable','file',$logFile);
}
$writer = new Zend_Log_Writer_Stream($logFile);
$logger = new Zend_Log($writer);
/*
 * log file - end
 */

if (!is_writable('./tmp/cache')) {
    
   simpleInvoicesError('notWriteable','file','./tmp/cache');
}
/*
 * Zend Framework cache section - start
 * -- must come after the tmp dir writeable check
 */
$backendOptions = array(
    'cache_dir' => './tmp/' // Directory where to put the cache files
);
                                   
// getting a Zend_Cache_Core object
$cache = Zend_Cache::factory('Core',
                             'File',
                             $frontendOptions,
                             $backendOptions);

//required for some servers
Zend_Date::setOptions(array('cache' => $cache)); // Active aussi pour Zend_Locale
/*
 * Zend Framework cache section - end
 */

$smartyViewPaths = array(
    '.',
    './templates/default/',
    './templates/',
    './custom/',
    './custom/default_template/',
    './include/jquery/',
    './modules/'
);
$smartyCachePath = './tmp/cache';
if (!is_writable($smartyCachePath)) {
	simpleInvoicesError("notWriteable", 'folder', $smartyCachePath);
}

if (!class_exists('Jenssegers\Blade\Blade')) {
	simpleInvoicesError('notWriteable', 'library', 'jenssegers/blade (run: composer update)');
}
require_once(__DIR__ . '/blade_view.php');
$smarty = new BladeView($smartyViewPaths, $smartyCachePath);
/*
 * Blade template engine init - end
 */


$path = pathinfo($_SERVER['REQUEST_URI'] ?? '');
//SC: Install path handling will need changes if used in non-HTML contexts  
$install_path = htmlsafe($path['dirname'] ?? '');


include_once('./config/define.php');

/*
 * Include another config file if required
 */
if( is_file('./config/custom.config.php') ){
     $config = new Zend_Config_Ini('./config/custom.config.php', $environment,true);
} else {
    $config = new Zend_Config_Ini('./config/config.php', $environment,true);	//added 'true' to allow modifications from db
}

//set up app with relevant php setting
date_default_timezone_set($config->phpSettings->date->timezone);
$errorReporting = $config->debug->error_reporting;
if (!is_int($errorReporting)) {
	$errorReporting = trim((string) $errorReporting);
	if (defined($errorReporting)) {
		$errorReporting = constant($errorReporting);
	} elseif (is_numeric($errorReporting)) {
		$errorReporting = (int) $errorReporting;
	} else {
		$errorReporting = E_ERROR;
	}
}
error_reporting($errorReporting);
ini_set('display_startup_errors', $config->phpSettings->display_startup_errors);  
ini_set('display_errors', $config->phpSettings->display_errors); 
ini_set('log_errors', $config->phpSettings->log_errors); 
ini_set('error_log', $config->phpSettings->error_log); 



$zendDb = Zend_Db::factory($config->database->adapter, array(
    'host'     => $config->database->params->host,
    'username' => $config->database->params->username,
    'password' => $config->database->params->password,
    'dbname'   => $config->database->params->dbname,
    'port'     => $config->database->params->port)
);

//include_once("./include/sql_patches.php");

include_once("./include/class/db.php");
include_once("./include/class/index.php");
include_once("./include/class/domain/id.php");
include_once("./include/class/siLocal.php");
spl_autoload_register(function ($class_name) {
    include "./include/class/".$class_name . '.php';
});

$db = db::getInstance();

include_once("./include/sql_queries.php");

// Blade modifiers are registered in BladeView::registerDirectives(); use {{ htmlsafe($var) }} etc. in templates
$install_tables_exists = checkTableExists(TB_PREFIX."biller");
if ($install_tables_exists == true)
{
	$install_data_exists = checkDataExists();
}

//TODO - add this as a function in sql_queries.php or a class file
//if ( ($install_tables_exists != false) AND ($install_data_exists != false) )
if ( $install_tables_exists != false )
{
	if (getNumberOfDoneSQLPatches() > "196")
	{
		// Only load core - extensions have been removed
		$sql = "SELECT * FROM ".TB_PREFIX."extensions WHERE name = 'core' AND (domain_id = :domain_id OR domain_id = 0) ORDER BY domain_id DESC LIMIT 1";
		$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);
		$core = $sth ? $sth->fetch() : null;
		if ($core) {
			$config->extension = new Zend_Config(array('core' => $core));
		}
	}
}

if (!isset($config->extension) || !$config->extension)
{
	$config->extension = new Zend_Config(array('core' => array(
		'id' => 0,
		'domain_id' => 0,
		'name' => 'core',
		'description' => 'Core part of Simple Invoices - always enabled',
		'enabled' => '1'
	)));
}

include_once('./include/language.php');

checkConnection();

include('./include/include_auth.php');
include_once('./include/manageCustomFields.php');
include_once("./include/validation.php");

//if authentication enabled then do acl check etc..
if ($config->authentication->enabled == 1 )
{
	include_once("./include/acl.php");
	include_once("./include/check_permission.php");
}

/*
Array: Early_exit
- Pages that don't need header or exit prior to adding the template add in here
*/
$early_exit = array();
$early_exit[] = "auth_login";
$early_exit[] = "api_cron";
$early_exit[] = "auth_logout";
$early_exit[] = "export_pdf";
$early_exit[] = "export_invoice";
$early_exit[] = "statement_export";
$early_exit[] = "invoice_template";
$early_exit[] = "payments_print";
#$early_exit[] = "reports_report_statement";
$early_exit[] = "documentation_view";
//$early_exit[] = "install_index";


switch ($module)
{
	case "export" :	
		$smarty_output = "fetch";
		break;
	default :
		$smarty_output = "display";
		break;
}

//get the url - used for templates / pdf
$siUrl = getURL();
//zend db

// Get extensions from DB, and update config array


//If using the folowing line, the DB settings should be appended to the config array, instead of replacing it (NOT TESTED!)
//$config->extension() = $DB_extensions;


include_once("./include/backup.lib.php");

$defaults = getSystemDefaults();
$smarty -> assign("defaults",$defaults);

?>
