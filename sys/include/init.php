<?php
/*
 * Zend framework init - start
 */

// ToDo: Remove this include
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(APPLICATION_PATH ) . "/sys/include/class"));

// Need the configuration beforehand
include_once(APPLICATION_PATH . '/config/define.php');

/*
 * Make sure the logging folder and file exist
 */
$logFile = APPLICATION_PATH . "/tmp/log/si.log";
// Create tmp log if it does not exist
if (!file_exists(dirname($logFile))) {
    if (!mkdir(dirname($logFile), 0770, true)) {
        simpleInvoicesError('notWriteable','folder', dirname($logFile));
    }
} elseif(!is_writeable(dirname($logFile))) {
    if(!chmod(dirname($logFile), 0770)) {
        simpleInvoicesError('notWriteable','folder', dirname($logFile));
    }
}
if (!is_file($logFile))
{
    $createLogFile = fopen($logFile, 'w') or die(simpleInvoicesError('notWriteable','folder', APPLICATION_PATH . '/tmp/log'));
    fclose($createLogFile);
}
if (!is_writable($logFile)) {

   simpleInvoicesError('notWriteable','file',$logFile);
}

/*
 * Make sure the cache folder exists
 */
if (!file_exists(APPLICATION_PATH . '/tmp/cache')) {
    if (!mkdir(APPLICATION_PATH . '/tmp/cache', 0770, true)) {
        simpleInvoicesError('notWriteable','folder', APPLICATION_PATH . '/tmp/cache');
    }
} elseif(!is_writeable(APPLICATION_PATH . '/tmp/cache')) {
    if(!chmod(APPLICATION_PATH . '/tmp/cache', 0770)) {
        simpleInvoicesError('notWriteable','folder', APPLICATION_PATH . '/tmp/cache');
    }
}

/*
 * Make sure the sessions folder exists
 */
if (isset($config->resources->session->save_path)) {
    if (!file_exists($config->resources->session->save_path)) {
        if (!mkdir($config->resources->session->save_path, 0770, true)) {
            simpleInvoicesError('notWriteable','folder', $config->resources->session->save_path);
        }
    } elseif(!is_writeable($config->resources->session->save_path)) {
        if(!chmod($config->resources->session->save_path, 0770)) {
            simpleInvoicesError('notWriteable','folder', $config->resources->session->save_path);
        }
    }    
}

/**
* Backward compatibility
* 
* Gateway between Bootstrap and Init
* Some global variables may be removed, however, to get a fast start
* we just get them from the Zend_Registry and go along as up until now
*/
$config = Zend_Registry::get('config');
$auth_session = Zend_Registry::get('auth_session');
$logger = Zend_Registry::get('logger');

// ToDo: Delete this once it can be fetched in another way
$baseUrl = $config->resources->frontController->baseUrl;
if ($baseUrl == '/') $baseUrl = '';
Zend_Registry::set('baseUrl', $baseUrl);

//start use of zend_cache
$frontendOptions = array(
    'lifetime' => 7200, // cache lifetime of 2 hours
    'automatic_serialization' => true
);

/*
 * Zend framework init - end
 */



/*
 * Smarty inint - start
 */

#ini_set('display_errors',true);

require_once("smarty/Smarty.class.php");
require_once("lib/paypal/paypal.class.php");

require_once('lib/HTMLPurifier/HTMLPurifier.standalone.php');
require_once('sys/include/functions.php');

//ob_start('addCSRFProtection');


/*
 * Zend Framework cache section - start
 * -- must come after the tmp dir writeable check
 */

// getting a Zend_Cache_Core object
try {
	$backendOptions = array(
		'cache_dir' => APPLICATION_PATH . '/tmp/cache/' // Directory where to put the cache files
	);
	$cache = Zend_Cache::factory('Core',
                             'File',
                             $frontendOptions,
                             $backendOptions);

    //required for some servers
    // This makes it active for Zend_Locale as well
	Zend_Date::setOptions(array('cache' => $cache));
}
catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

/*
 * Zend Framework cache section - end
 */

 $smarty = new Smarty();

$smarty->debugging = false;
$smarty->cache_lifetime = 0;

// Set paths for Smarty
$smarty->setTemplateDir(realpath(dirname(APPLICATION_PATH) . '/sys/templates/'));
$smarty->setCacheDir(realpath(APPLICATION_PATH . '/tmp/cache/'));
$smarty->setCompileDir(realpath(APPLICATION_PATH . '/tmp/cache/'));

//adds own smarty plugins
$smarty->addPluginsDir(dirname(__FILE__) . '/smarty_plugins/');

//add stripslash smarty function
$smarty->registerPlugin('modifier', 'unescape', 'stripslashes');
$smarty->registerPlugin('modifier', 'capitalise', 'ucwords');

$smarty->registerPlugin('modifier', "siLocal_number", array("siLocal", "number"));
$smarty->registerPlugin('modifier', "siLocal_number_clean", array("siLocal", "number_clean"));
$smarty->registerPlugin('modifier', "siLocal_number_trim", array("siLocal", "number_trim"));
$smarty->registerPlugin('modifier', "siLocal_number_formatted", array("siLocal", "number_formatted"));
$smarty->registerPlugin('modifier', "siLocal_date", array("siLocal", "date"));
$smarty->registerPlugin('modifier', 'htmlsafe', 'htmlsafe');
$smarty->registerPlugin('modifier', 'urlsafe', 'urlsafe');
$smarty->registerPlugin('modifier', 'urlencode', 'urlencode');
$smarty->registerPlugin('modifier', 'outhtml', 'outhtml');
$smarty->registerPlugin('modifier', 'htmlout', 'outhtml'); //common typo
$smarty->registerPlugin('modifier', 'urlescape', 'urlencode'); //common typo


$path = pathinfo($_SERVER['REQUEST_URI']);
//SC: Install path handling will need changes if used in non-HTML contexts
$install_path = htmlsafe($path['dirname']);


//set up app with relevant php setting
date_default_timezone_set($config->phpSettings->date->timezone);
error_reporting($config->debug->error_reporting);
ini_set('display_startup_errors', $config->phpSettings->display_startup_errors);
ini_set('display_errors', $config->phpSettings->display_errors);
ini_set('log_errors', $config->phpSettings->log_errors);
ini_set('error_log', $config->phpSettings->error_log);

//after config is loaded - do auth
require_once($include_dir . 'sys/include/include_auth.php');

include_once("sys/include/class/db.php");
include_once("sys/include/class/index.php");
$db = db::getInstance();

include_once("sys/include/sql_queries.php");


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
	    $sql="SELECT * from ".TB_PREFIX."extensions WHERE (domain_id = :id OR domain_id =  0 ) ORDER BY domain_id ASC";
	    $sth = dbQuery($sql,':id', $auth_session->domain_id ) or die(htmlsafe(end($dbh->errorInfo())));

	    while ( $this_extension = $sth->fetch() )
	    {
	    	$DB_extensions[$this_extension['name']] = $this_extension;
	    }
	    $config->extension = $DB_extensions;
	}
}

/*
 * Moved the extensions init loop out of index so can load extensions stuff before acl code
 */
if ($config->extension != null)
{
	foreach($config->extension as $extension)
	{
		/*
		* If extension is enabled then continue and include the requested file for that extension if it exists
		*/
		if($extension->enabled == "1")
		{
			//echo "Enabled:".$value['name']."<br><br>";
			if(file_exists($include_dir . "sys/extensions/$extension->name/include/init.php"))
			{
				require_once("sys/extensions/$extension->name/include/init.php");
			}
		}
	}
}
// If no extension loaded, load Core
if (! $config->extension)
{
	$extension_core = new Zend_Config(array('core'=>array(
		'id'=>1,
		'domain_id'=>1,
		'name'=>'core',
		'description'=>'Core part of Simple Invoices - always enabled',
		'enabled'=>1
		)));
	$config->extension = $extension_core;
}

require_once($include_dir . 'sys/include/language.php');


//add class files for extensions


checkConnection();

require_once($include_dir . 'sys/include/manageCustomFields.php');
require_once($include_dir . "sys/include/validation.php");

//if authentication enabled then do acl check etc..
if ($config->authentication->enabled == 1 )
{
	require_once($include_dir . "sys/include/acl.php");
	require_once($include_dir . "sys/include/check_permission.php");
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
$early_exit[] = "biller_upload-logo";
//$early_exit[] = "install_index";
$early_exit[] = "export_payment";


$module = isset($_GET['module'])?$_GET['module']:null;
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


include_once($include_dir . "sys/include/backup.lib.php");

$defaults = getSystemDefaults();
$smarty->assignGlobal("defaults",$defaults);
$smarty->assignGlobal('baseUrl', $config->resources->frontController->baseUrl);
// Get rid of some errors
$smarty->assign('subPageActive','');
?>