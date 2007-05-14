<?php
session_start();

$language = $_SESSION['language'];

// +-----------------------------------------------------------------------+
// | Simple Invoices                                                       |
// | Licence: GNU General Public License 2.0                               |
// +-----------------------------------------------------------------------+

// Select the language
include('lang/lang_'.$language.'.php');
?>	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<title>Simple Invoices | Installer</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="./css/screen.css" media="all"/>

<div id="Wrapper">
	<div id="Container">

		<div class="Full">    
			<div class="col">
			<h1>Simple Invoices :: installer</h1>
			<hr />

<?php

// _POST Control
if($Click == 'on') {
    if($_POST['host'] == '') { $Erreur['DBHost'] = $LANG['error_DBHost']; $error = true; }
	else {
		$host = trim($_POST['host']);
		$_SESSION['host'] = $host;
	}

	if($_POST['dbname'] == '') { $Erreur['DBName'] = $LANG['error_DBName']; $error = true; }
	else {
		$dbname = trim($_POST['dbname']);
		$_SESSION['dbname'] = $dbname;
	}

    if($_POST['username'] == '') { $Erreur['DBUsername'] = $LANG['error_DBUsername']; $error = true; }
	else {
		$username = trim($_POST['username']);
		$_SESSION['username'] = $username;
	}

    if($_POST['passwd'] == '') { $Erreur['DBPassword'] = $LANG['error_DBPassword']; $error = true; } 
	else {
		$passwd = trim($_POST['passwd']);
		$_SESSION['passwd'] = $passwd;
	}

    if($_POST['prefix'] == '') { $Erreur['prefix'] = $LANG['error_prefix']; $error = true; } 
	else {	
		$table_prefix = trim($_POST['prefix']);
		$_SESSION['table_prefix'] = $table_prefix;
	}

$_SESSION['DBHost_error'] = $Erreur['DBHost'];
$_SESSION['DBName_error'] = $Erreur['DBName'];
$_SESSION['DBUsername_error'] = $Erreur['DBUsername'];
$_SESSION['DBPassword_error'] = $Erreur['DBPassword'];
$_SESSION['prefix_error'] = $Erreur['prefix'];

if($error == true) echo '<meta http-equiv="refresh" content="url=connection.php" />';

if(!isset($Erreur)) {

// connection
$connection = mysql_connect($host, $username, $passwd) or die($LANG['unableConnectDb'] . mysql_error());


// Select mysql version
if (version_compare(phpversion(), "5.0", ">=")) {
	$mysql5_create_table = "sql/simpleinvoices.sql"; //sql query to create tables
	$sql_version = $mysql5_create_table;
	$_SESSION['sql_version'] = $sql_version; }
else {
	$mysql4_create_table = "sql/old/SimpleInvoicesDatabase-MySQL4_0.sql"; //sql query to create tables
	$sql_version = $mysql4_create_table;
	$_SESSION['sql_version'] = $sql_version; }


function parse_mysql_dump($url, $ignoreerrors = false) {
$file_content = file($url);
//print_r($file_content);
$query = "";
foreach($file_content as $sql_line) {
	$tsl = trim($sql_line);
	if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
		$query .= $sql_line;
		if(preg_match("/;\s*$/", $sql_line)) {
			$result = mysqlQuery($query);
		if (!$result && !$ignoreerrors) die(mysql_error());
			$query = "";
			}
		}
	}
}

// Form action DB
$submit_array = array_keys($_POST['submit']);
$action = $submit_array[0];
switch ($action) {
	case 'create':
		// Created a new DB 
		$query = mysqlQuery("CREATE DATABASE IF NOT EXISTS ". $dbname) or die ($LANG['existingDb'] . mysql_error());
		
		// Select this new DB
		$db_selected = mysql_select_db($dbname, $connection);
		if (!$db_selected) {
			die ($LANG['unableSelectDb'] . mysql_error());
		}
		// Create tables
		parse_mysql_dump($sql_version, $ignoreerrors = false);
		break;	
		
	case 'drop':
		// Select an existing DB
		$db_selected = mysql_select_db($dbname, $connection);
		if (!$db_selected) {
			die ($LANG['unableSelectDb'] . mysql_error());
		}
		// Drop tables
		$dropTables = "sql/drop.sql";
		parse_mysql_dump($dropTables, $ignoreerrors = false);
		
		// Create tables
		parse_mysql_dump($sql_version, $ignoreerrors = false);
		break;
}

// close connection
mysql_close($connection);

    } 
}

?>

			<form name="insertion" method="post" action="insertion.php">
			<p>
				<input type="submit" name="submit[insertNo]" value="<?php echo $LANG['insertDataNo'] ?>">
				<input type="submit" name="submit[insertYes]" value="<?php echo $LANG['insertDataYes'] ?>"> 
			</p>
			</form>				
				
			<hr />

			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>

</body>
</html>