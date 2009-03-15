<?php

/* 
 * Zend framework init - start
 */
set_include_path(get_include_path() . PATH_SEPARATOR . "./include/class");
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/");
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/pdf");
require_once 'Zend/Loader.php';

Zend_Loader::registerAutoload();


//session_start();
Zend_Session::start();
$auth_session = new Zend_Session_Namespace('Zend_Auth');


/* 
 * Zend framework init - end
 */



/* 
 * Smarty inint - start
 */

#ini_set('display_errors',true);

require_once("smarty/Smarty.class.php");

include_once('./include/functions.php');

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

$smarty = new Smarty();

#$smarty->debugging = TRUE;

//cache directory. Have to be writeable (chmod 777)
$smarty -> compile_dir = "tmp/cache";
if(!is_writable($smarty -> compile_dir)) {
	simpleInvoicesError("notWriteable", 'folder', $smarty -> compile_dir);
	//exit("Simple Invoices Error : The folder <i>".$smarty -> compile_dir."</i> has to be writeable");
}

//adds own smarty plugins
$smarty->plugins_dir = array("plugins","include/smarty_plugins");

//add stripslash smarty function
$smarty->register_modifier("unescape","stripslashes");
/* 
 * Smarty inint - end
 */


$path = pathinfo($_SERVER['REQUEST_URI']);
//SC: Install path handling will need changes if used in non-HTML contexts
$install_path = htmlspecialchars($path['dirname']);


include_once('./config/define.php');

$config = new Zend_Config_Ini('./config/config.ini', $environment);
/*
 * Include another config file if required
 */
if($environment != 'production') {
     $config = new Zend_Config_Ini('./config/'.$environment.'.config.ini', $environment);
}


$zendDb = Zend_Db::factory($config->database->adapter, array(
    'host'     => $config->database->params->host,
    'username' => $config->database->params->username,
    'password' => $config->database->params->password,
    'dbname'   => $config->database->params->dbname)
);

//include_once("./include/sql_patches.php");
include_once("./include/sql_queries.php");

$smarty->register_modifier("siLocal_number", array("siLocal", "number"));
$smarty->register_modifier("siLocal_number_trim", array("siLocal", "number_trim"));
include_once('./include/language.php');

include_once('./include/functions.php');

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
$early_exit[] = "auth_logout";
$early_exit[] = "export_pdf";
$early_exit[] = "export_invoice";
$early_exit[] = "invoice_template";


switch ($_GET['module'])
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

