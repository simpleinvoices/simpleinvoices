<?php
/* 
 * Zend framework init - start
 */
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/class");
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/");
// PDF library path no longer needed - using Composer autoloader
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/");

// Load Composer autoloader for all managed libraries
require_once('./vendor/autoload.php');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('PHPSESSID');
    session_start([
        'cookie_secure' => false,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

include_once('./include/class/LegacyAuthSession.php');
$auth_session = new LegacyAuthSession('SI_Auth');


/* 
 * Zend framework init - end
 */



#ini_set('display_errors',true);

include_once('./include/functions.php');
include_once('./include/report_chart_guard.php');

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
	include_once('./include/class/LegacyLogger.php');
	$logger = new LegacyLogger($logFile);
/*
 * log file - end
 */

if (!is_writable('./tmp/cache')) {
    
   simpleInvoicesError('notWriteable','file','./tmp/cache');
}

$bladeViewPaths = array(
    '.',
    './templates/default/',
    './templates/',
    './custom/',
    './custom/default_template/',
    './include/js/',
    './modules/'
);
$bladeCachePath = './tmp/cache';
if (!is_writable($bladeCachePath)) {
	simpleInvoicesError("notWriteable", 'folder', $bladeCachePath);
}

if (!class_exists('Jenssegers\Blade\Blade')) {
	simpleInvoicesError('notWriteable', 'library', 'jenssegers/blade (run: composer update)');
}
require_once(__DIR__ . '/blade_view.php');
/**
 * Global Blade view for the request (assign / display / fetch). Used by index.php and modules.
 * @var BladeView
 */
$bladeView = new BladeView($bladeViewPaths, $bladeCachePath);
/*
 * Blade template engine init - end
 */


$path = pathinfo($_SERVER['REQUEST_URI'] ?? '');
//SC: Install path handling will need changes if used in non-HTML contexts  
$install_path = htmlsafe($path['dirname'] ?? '');


include_once('./config/define.php');
include_once('./include/class/ConfigLoader.php');

/*
 * Include another config file if required
 */
	if( is_file('./config/custom.config.php') ){
	     $config = ConfigLoader::load('./config/custom.config.php', $environment);
	} else {
	    $config = ConfigLoader::load('./config/config.php', $environment);
	}	//added 'true' to allow modifications from db

// Global database adapter type: 'mysql', 'pgsql', or 'sqlite'
$db_server = substr($config->database->adapter, 4);

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
require_once __DIR__ . '/global_app_settings.php';

// Blade modifiers are registered in BladeView::registerDirectives(); use {{ htmlsafe($var) }} etc. in templates
$install_tables_exists = checkTableExists(TB_PREFIX."biller");
mergeGlobalAppSettingsIntoConfig($config);
// $install_data_exists is set after include_auth.php (domain-scoped essential data check)

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
			$config->extension = ConfigData::fromArray(['core' => $core]);
		}
	}
}

if (!isset($config->extension) || !$config->extension)
{
		$config->extension = ConfigData::fromArray(['core' => array(
			'id' => 0,
			'domain_id' => 0,
			'name' => 'core',
			'description' => 'Core part of Simple Invoices - always enabled',
			'enabled' => '1'
		)]);
}

include_once('./include/language.php');

checkConnection();

include('./include/include_auth.php');

if ((int) ($config->authentication->enabled ?? 0) === 1 && empty($auth_session->fake_auth)) {
	require_once __DIR__ . '/user_ui_language.php';
	si_apply_user_ui_language();
}

$install_data_exists = false;
if ($install_tables_exists == true) {
	$install_data_exists = checkDataExists();
}

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
$early_exit[] = "auth_customer_login";
$early_exit[] = "api_cron";
$early_exit[] = "auth_logout";
$early_exit[] = "export_pdf";
$early_exit[] = "export_invoice";
$early_exit[] = "export_payment";
$early_exit[] = "statement_export";
$early_exit[] = "invoice_template";
$early_exit[] = "payments_print";
#$early_exit[] = "reports_report_statement";
$early_exit[] = "documentation_view";
$early_exit[] = "user_save_ui_language";
//$early_exit[] = "install_index";
// Backup download must run before any HTML is output so it can send file headers
if ($module === 'options' && $view === 'backup_database' && ($_POST['op'] ?? '') === 'backup_db') {
	$early_exit[] = 'options_backup_database';
}


switch ($module)
{
	case "export" :
		$blade_output = "fetch";
		break;
	default :
		$blade_output = "display";
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
$bladeView -> assign("defaults",$defaults);

?>
