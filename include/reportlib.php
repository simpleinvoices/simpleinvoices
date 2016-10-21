<?php
// Eliminate code on 20161014 as uses PHPReportMaker class that doesn't exist.
// So likely that the code isn't used.
/*
require_once("./include/init.php");
global $db_server, $config;

// needed for /library/phpreports to function
$val = ini_get("include_path");
// PATH_SEPARATOR is ":" for non-windows and ":" for windows
$val = $val . PATH_SEPARATOR . "./library/phpreports";
ini_set("include_path", $val);
    
$db_server=substr($config->database->adapter, 4);
require_once("PHPReportMaker.php");

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

$oRpt = new PHPReportMaker();
$oRpt->setUser($config->database->params->username);
$oRpt->setPassword($config->database->params->password);
$oRpt->setDatabase($config->database->params->dbname);
$oRpt->setConnection($config->database->params->host.':'.$config->database->params->port);  
$oRpt->setDatabaseInterface($db_server); // set as $db_server in trunk
*/
