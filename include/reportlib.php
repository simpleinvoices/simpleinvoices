<?
require_once("./config/config.php");

// needed for /libs/phpreports to function
$val = ini_get("include_path");
// PATH_SEPARATOR is ":" for non-windows and ":" for windows
$val = $val . PATH_SEPARATOR . "./library/phpreports";
ini_set("include_path", $val);

require_once("PHPReportMaker.php");

/* The Export code - supports any file extensions - excel/word/open office - what reads html */
/* this does not work for now
if (isset($_GET['export'])) {
	$template = "export";
	$file_extension = $_GET['export'];
	header("Content-type: application/octet-stream");
//	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=test.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
*/
/* End Export code */

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

	$oRpt = new PHPReportMaker();

	$oRpt->setUser($db_user);
	$oRpt->setPassword($db_password);
	$oRpt->setConnection($db_host);  
	$oRpt->setDatabaseInterface("mysql"); // set as $db_server in trunk
	$oRpt->setDatabase($db_name);
?>