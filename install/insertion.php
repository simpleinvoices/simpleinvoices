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

// Selection de la langue de l'installeur
include('lang/lang_'.$language.'.php');

//sql query to populate tables with sample data version 4
$mysql_4data = fopen("sql/SimpleInvoicesDatabase-MySQL4_0Data.sql", "r");
//sql query to populate tables with sample data version 5
$mysql_5data = fopen("sql/SimpleInvoicesDatabaseData.sql", "r");

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

		break;	
		
	case 'insertYes':
		if($sql_version == $mysql4_create_table)
			$sqlTableData = mysql_query($connection, $mysql_4data);
		else
			$sqlTableData = mysql_query($connection, $mysql_5data);
		break;	
}

// Close connection
mysql_close($connection);


// Création du contenu du fichier config.php

//Modification des droits d'accès du dossier ""config"
chmod("../config", 0777);

// Open file "content"
$openFileContent = fopen("./content.php", "r");

// Ecriture du contenu dans la variable "$contents"
$contents = file_get_contents("./content.php");

// Contrôle si le fichier est accessible en écriture
if (is_writable($fileConfig)) {

	// ouverture du fichier "config.php"
    if (!$openFileConfig = fopen('./config/config.php', 'r+')) {
		echo $LANG['OpenFileFailure'];
		exit;
	}

	// Writing
	if (fwrite($openFileConfig, $contents) === TRUE) {
		echo $LANG['writingSuccess'];

		// Closing
		fclose($openFile);
    }
	else {
    	echo $LANG['writingNoSuccess'];
    	exit; 
	}
} 

	// destruction de la session
	// session_destroy();
	// unset($_SESSION);
	
?>
