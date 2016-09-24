<?php
/*
 * Script: ./extensions/matts_luxury_pack/init.php
 * 	Initialization
 *
 * Authors:
 *	 git0matt@gmail.com
 *
 * Last edited:
 * 	 2016-09-16
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
if (!isset($matthere))
{
	$matthere = realpath(dirname(__FILE__));

	//echo '<script>alert("matts_luxury_pack-init:req ['.$_SERVER['REQUEST_TIME_FLOAT'].']['.$start_time.']")</script>';

	//include_once "$matthere/sql_queries.php";	//not needed here
	//set_include_path(get_include_path() . PATH_SEPARATOR . "./$matthere/");	// files are not loaded on-demand
	set_include_path(get_include_path() . PATH_SEPARATOR . "$matthere/class/");	// load classes on-demand
	$mytime = new mytime;	// begin execution timer
	error_log('loading //matts_luxury_pack//init.php at '. $mytime->took());//date("Y-m-d H:i:s", 

	modifyDB::log();
	$array0to9 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);	// often used array
	$pagerows = array(5, 10, 15, 20, 25, 30, 35, 50, 100, 500);	// rows-per-page array
	$smarty->assign("version_name", $config->version->name);	// put version_name into page header template
	include_once ($matthere. '/customer.functs.php');
	//$logger->log('include_once ('. $matthere. '/customer.functs.php)', Zend_Log::INFO);
	error_log('include_once ('. $matthere. '/customer.functs.php) in '. $mytime->took());
	include_once ($matthere. '/payments.functs.php');
	//$logger->log('include_once ('. $matthere. '/payments.functs.php)', Zend_Log::INFO);
	error_log('include_once ('. $matthere. '/payments.functs.php) in '. $mytime->took());

	/*
	function myNoticeStrictHandler($errstr, $errfile, $errline) {//$errno=null, 
	}
	set_error_handler('myNoticeStrictHandler', E_NOTICE | E_STRICT);
	*/
	function DBcolumnExists($table, $column) {	// use another name for ...
		return checkFieldExists($table, $column);
	}
}