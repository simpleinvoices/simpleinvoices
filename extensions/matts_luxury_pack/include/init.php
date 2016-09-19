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
set_include_path(get_include_path() . PATH_SEPARATOR . "./extensions/matts_luxury_pack/include/class/");	// load classes
/*include_once ('extensions/matts_luxury_pack/include/class/myinvoice.php');
$logger->log('include_once ("extensions/matts_luxury_pack/include/class/myinvoice.php")', Zend_Log::INFO);
include_once ('extensions/matts_luxury_pack/include/class/myproduct.php');
$logger->log('include_once ("extensions/matts_luxury_pack/include/class/myproduct.php")', Zend_Log::INFO);
include_once ('extensions/matts_luxury_pack/include/class/myexport.php');
$logger->log('include_once ("extensions/matts_luxury_pack/include/class/myexport.php")', Zend_Log::INFO);
*/
//include_once('extensions/matts_luxury_pack/include/sql_queries.php');

/*if (!function_exists ('addDatabaseColumn'))
{		^*TAKES TOO LONG*/
	function addDatabaseColumn ($column, $table, $type, $length, $cannull=false, $def_value="", $after="")
	{
		global $LANG, $dbh;

		$sql = "SELECT data_type FROM information_schema.columns WHERE table_name='$table' AND column_name='$column';";
	error_log ("exists($table.$column)...$sql");
		if (($sth = $dbh->query ($sql)) === false)
		{
			// Non-critical error so continue with next action.
			error_log ("Error: ".print_r($sth->errorInfo(),true)." in matts_luxury_pack - addDatabaseColumn: $sql");
		} else
		{
			$row = $sth->fetch (PDO::FETCH_ASSOC);
			if (strtolower($row['data_type']) != strtolower($type))
			{
				$length = strstr($length, '.', ',');//str_replace('.', ',', $length);
				$sql = "ALTER TABLE `$table` ADD COLUMN `$column` $type( $length )";
				$sql.= $cannull ? " NOT NULL" : " NULL";
				$sql.= isset($def_value) ? " DEFAULT '$def_value'" : "";
				$sql.= $after ? " AFTER `$after`" : "";
				$sql.= ";";
	error_log ("add($table.$column)...$sql|");
				if (($sth = $dbh->query ($sql)) === false)
				{
					// Non-critical error so continue with next action.
					if ($sth)      $error = print_r($sth->errorInfo(), true);
//					error_log ("Error: $error in matts_luxury_pack - addDatabaseColumn: $sql");
				}
			}
		}
		return true;
	}
/*}			*TAKES TOO LONG*/

modifyDB::log();
$array0to9 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);	// often used array
$pagerows = array(5, 10, 15, 20, 25, 30, 35, 50, 100, 500);	// rows-per-page array
$smarty->assign("version_name", $config->version->name);	// put version_name into page header template

include_once ('extensions/matts_luxury_pack/include/customer.functs.php');
$logger->log('include_once ("extensions/matts_luxury_pack/include/customer.functs.php")', Zend_Log::INFO);
include_once ('extensions/matts_luxury_pack/include/payments.functs.php');
$logger->log('include_once ("extensions/matts_luxury_pack/include/payments.functs.php")', Zend_Log::INFO);
/*
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$start_time = microtime_float();
//echo '<script>alert("matts_luxury_pack-init:req ['.$_SERVER['REQUEST_TIME_FLOAT'].']['.$start_time.']")</script>';
*/

/*
function myNoticeStrictHandler($errstr, $errfile, $errline) {//$errno=null, 
}
set_error_handler('myNoticeStrictHandler', E_NOTICE | E_STRICT);
*/
function DBcolumnExists($table, $column) {	// use another name for ...
	return checkFieldExists($table, $column);
}
