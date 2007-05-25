<?php


require_once("./modules/include/js/lgplus/php/chklang.php");
require_once("./modules/include/js/lgplus/php/settings.php");

if (isset($_GET['location']) && $_GET['location'] === 'pdf' ) {
	include('./include/include_auth.php');
}
include_once('./config/config.php');

$conn = mysql_connect("$db_host","$db_user","$db_password");
if (!$conn) {
	die('<br>
		===========================================<br>
		Simple Invoices database connection problem<br>
		===========================================<br>
		Could not connect to the Simple Invoices database<br><br>
		Please refer to the following Mysql error for for to fix this: <b>ERROR :' . mysql_error() . '</b><br><br>
		If this is an Access denied error please make sure that the db_host, db_name, db_user, and db_password in config/config.php are correct 
		<br>
		===========================================<br>
		');
}

$test_db_selection = mysql_select_db("$db_name",$conn);

if (!$test_db_selection) {
	die('<br>
		===========================================<br>
		Simple Invoices database selection problem<br>
		===========================================<br>
		Could not connect to the Simple Invoices database<br><br>
		Please make sure that the database name($db_name) in config/config.php is correct
		<br>
		===========================================<br>
		');
}
mysql_select_db("$db_name",$conn);


include_once('./include/functions.php');

include_once("./include/validation.php");

?>
