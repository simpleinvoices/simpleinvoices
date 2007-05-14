<?php
session_start();

	$language = $_SESSION['language'];
	$mysql4_create_table = $_SESSION['mysql4_create_table'];
	$sql_version = $_SESSION['sql_version'];
	$host = $_SESSION['host'];
	$username = $_SESSION['username'];
	$passwd = $_SESSION['passwd'];
	$dbname = $_SESSION['dbname'];
	$table_prefix = $_SESSION['table_prefix'];

// +-----------------------------------------------------------------------+
// | Simple Invoices                                                       |
// | Licence: GNU General Public License 2.0                               |
// +-----------------------------------------------------------------------+

// Select the language 
include('lang/lang_'.$language.'.php');


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

// Connection
$connection = mysql_connect($host, $username, $passwd)
or die($LANG['unableConnectDb'] . mysql_error());

// Select DB
	$db_selected = mysql_select_db($dbname, $connection);
	if (!$db_selected) {
		die ($LANG['unableSelectDb'] . mysql_error());
}

// Form action DB
$submit_array = array_keys($_POST['submit']);
$action = $submit_array[0];
switch ($action)
{
	case 'insertNo':
		echo $LANG['thanks']."<br />";
		break;	
		
	case 'insertYes':
	//sql query to populate tables with sample data version 4
	$mysql_4data = "sql/old/SimpleInvoicesDatabase-MySQL4_0Data.sql";
	
	//sql query to populate tables with sample data version 5
	$mysql_5data = "sql/simpleinvoicesDemoData.sql";

		if($sql_version == $mysql4_create_table)
			parse_mysql_dump($mysql_4data, $ignoreerrors = false);
		else
			parse_mysql_dump($mysql_5data, $ignoreerrors = false);
			
		echo $LANG['thanks']."<br />";
		break;	
}

// Close connection
mysql_close($connection);


// Création du contenu du fichier config.php

//Modification des droits d'accès du dossier "config"
chmod("../config", 0777);
chmod("../config/config.php", 0666);

require_once('./content.php');

$fileConfigOpen = fopen('../config/config.php', 'wb+');
	if(!$fileConfigOpen)
		echo $LANG['OpenFileFailure'];
	else
		$fileConfigWriting = fwrite($fileConfigOpen, $content);
		
	if ($fileConfigWriting === TRUE)
		echo $LANG['writingSuccess'];
	else
		echo $LANG['writingNoSuccess'];

fclose($fileConfigOpen);

// destruction de la session
// session_destroy();
// unset($_SESSION);
	
?>
<!-- redirection after 4 seconds -->
<meta http-equiv="refresh" content="4;url=../index.php" />
