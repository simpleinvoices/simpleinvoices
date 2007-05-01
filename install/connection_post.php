<?php
session_start();

$language = $_SESSION['language'];

// +-----------------------------------------------------------------------+
// | Simple Invoices                                                       |
// | Licence: GNU General Public License 2.0                               |
// +-----------------------------------------------------------------------+

// Selection de la langue de l'installeur
include('lang/lang_'.$language.'.php');
?>	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<title>Simple Invoices | Installer</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="all"/>


	<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
	<!--[if lte IE 7]>
	<style type="text/css" media="screen, projection">
	    body {
		font-size: 100%; /* resizable fonts */
	    }
	</style>
	<![endif]-->


<div id="Wrapper">
	<div id="Container">

		<div class="Full">    
			<div class="col">
			<h1>Simple invoices :: installer</h1>
			<hr></hr>

<?php
$mysql4_create_table = fopen("sql/SimpleInvoicesDatabase-MySQL4_0.sql", "r"); //sql query to create tables
$mysql5_create_table = fopen("sql/SimpleInvoicesDatabase.sql", "r"); //sql query to create tables
$_SESSION['mysql4_create_table'] = $mysql4_create_table;
$_SESSION['mysql5_create_table'] = $mysql5_create_table;

$dropTables = fopen("sql/drop.sql", "r");

	// _POST Control
	if(isset($_POST['host']) && isset($_POST['username']) && isset($_POST['passwd']) && isset($_POST['dbname']) && isset($_POST['prefix'])) {
		$host = $_POST['host'];
		$username = $_POST['username'];
		$passwd = $_POST['passwd'];
		$dbname = $_POST['dbname'];
		$table_prefix = $_POST['prefix'];
		
		$_SESSION['host'] = $host;
		$_SESSION['username'] = $username;
		$_SESSION['passwd'] = $passwd;
		$_SESSION['dbname'] = $dbname;
		$_SESSION['table_prefix'] = $table_prefix;
	}

	// connection
	$connection = mysql_connect($host, $username, $passwd)
    or die($LANG['unableConnectDb'] . mysql_error());

	// Select mysql version
	$version = mysql_get_server_info(); // no authorized access :-(
	$_SESSION['sql_version'] = $sql_version;
		if(substr($version, 0, 1) < 5)
			$sql_version = $mysql4_create_table;
		else
			$sql_version = $mysql5_create_table;

	// Form action DB
	$submit_array = array_keys($_POST['submit']);
	$action = $submit_array[0];
	switch ($action)
	{
		case 'create':
			$query = mysql_query("CREATE DATABASE IF NOT EXISTS ". $dbname) or die ($LANG['existingDb'] . mysql_error());
			$queryCreateTable = mysql_query($sql_version, $connection) or die (mysql_error()); //bug
			break;	
			
		case 'drop':
			$query = mysql_query($dropTables, $dbname) or die ($LANG['dropDbError'] . mysql_error());
			break;	
	}

	// Select DB
	$db_selected = mysql_select_db($dbname, $connection);
	if (!$db_selected) {
		die ($LANG['unableSelectDb'] . mysql_error());
	}

	// close connection
	mysql_close($connection);

?>

			<form name="insertion" method="post" action="insertion.php">
			<p>
				<input type="submit" name="submit[insertNo]" value="<?php echo $LANG['insertDataNo'] ?>">
				<input type="submit" name="submit[insertYes]" value="<?php echo $LANG['insertDataYes'] ?>"> 
			</p>
			</form>				
				
			<hr></hr>

			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>

</body>
</html>