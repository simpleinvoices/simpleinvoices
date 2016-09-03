<?php
/*
* Script: ./extensions/matts_luxury_pack/include/init.php
* 	initialization
*
* Authors:
*	 yumatechnical@gmail.com
*
* Last edited:
* 	 2016-08-31
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//global $LANG;

$array0to9 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
$pagerows = array(5, 10, 15, 20, 25, 30, 35, 50, 100, 500);
$smarty->assign("version_name", $config->version->name);

//include_once './extensions/<THIS NAME>/include/sql_queries.php';	// not active yet...

/*
function myNoticeStrictHandler($errstr, $errfile, $errline) {//$errno=null, 
}
set_error_handler('myNoticeStrictHandler', E_NOTICE | E_STRICT);
*/
function DBcolumnExists($table, $column) {
	return checkFieldExists($table, $column);
}
/*if (!function_exists ('addDatabaseColumn'))
{		^*TAKES TOO LONG*/
	function addDatabaseColumn ($column, $table, $type, $length, $cannull=false, $default="", $after="")
	{
		global $LANG, $dbh;

		$sql = "SELECT data_type FROM information_schema.columns WHERE table_name='$table' AND column_name='$column';";
	error_log ("testing...$sql|");
		if (($sth = $dbh->query ($sql)) === false)
		{
			// Non-critical error so continue with next action.
			error_log ("Error: ".print_r($sth->errorInfo(),true)." in matts_luxury_pack - addDatabaseColumn: $sql");
		} else
		{
			$row = $sth->fetch (PDO::FETCH_ASSOC);
			if (strtolower($row['data_type']) != strtolower($type))
			{
				$length = str_replace('.', ',', $length);
				$sql = "ALTER TABLE `$table` ADD COLUMN `$column` $type( $length )";
				$sql.= $cannull ? " NOT NULL" : " NULL";
				$sql.= $default ? " DEFAULT '$default'" : "";
				$sql.= $after ? " AFTER `$after`" : "";
				$sql.= ";";
	error_log ("no! trying...$sql|");
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

include_once ('extensions/matts_luxury_pack/include/class/myexport.php');

/********************* customer section ***********************/
//include_once ('extensions/matts_luxury_pack/include/class/mycustomer.php');
include_once ('extensions/matts_luxury_pack/include/customer.functs.php');

if (!DBcolumnExists(TB_PREFIX."customers", "credit_card_cvc")) {
	$dbh->beginTransaction();
	$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers ADD credit_card_cvc INT(11) NOT NULL AFTER credit_card_number");
	//My SQL / Oracle (prior version 10G):
	$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers MODIFY COLUMN credit_card_expiry_year INT(11) NULL DEFAULT NULL");
	$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers MODIFY COLUMN credit_card_expiry_month INT(11) NULL DEFAULT NULL");
	//SQL Server / MS Access:
	//ALTER TABLE table_name ALTER COLUMN column_name datatype
	//Oracle 10G and later:
	//ALTER TABLE table_name MODIFY column_name datatype
	$dbh->commit();
}
//addDatabaseColumn ('price_list', TB_PREFIX.'customers', 'int', 11);
if (!DBcolumnExists(TB_PREFIX."customers", "price_list")) {
	$dbh->beginTransaction();
	$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers ADD COLUMN price_list INT(11) NOT NULL");
	$dbh->commit();
}

/******************************** product section **************************/
include_once ('extensions/matts_luxury_pack/include/class/myproduct.php');

addDatabaseColumn ('unit_list_price2', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);
addDatabaseColumn ('unit_list_price3', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);
addDatabaseColumn ('unit_list_price4', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);

/******************************** invoice section *******************************/
include_once ('extensions/matts_luxury_pack/include/class/myinvoice.php');

addDatabaseColumn ('ship_to_customer_id', TB_PREFIX.'invoices', 'int', 11, false, 0, 'customer_id');
addDatabaseColumn ("terms", TB_PREFIX."invoices", "varchar", 100);

/****************************** payments section *********************************/
//include ('extensions/matts_luxury_pack/include/class/mypayments.php');
include_once ('extensions/matts_luxury_pack/include/payments.functs.php');
