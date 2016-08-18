<?php

// /simple/extensions/matts_luxury_pack/modules/iframe_customers/add.php

if (!defined("BROWSE"))		define("BROWSE","browse");

$cwd = getcwd();
chdir(dirname(__FILE__)."/../../../..");
require_once("./include/init_pre.php");
require_once("./include/init.php");
//require_once("./include/sql_queries.php");
//chdir(dirname(__FILE__)."/../..");

$smarty -> assign("dir",dirname(__FILE__));
$smarty -> assign("uri",dirname($_SERVER['REQUEST_URI']));
$smarty -> assign("config",$config); // to toggle the login / logout button visibility in the menu
//$smarty -> assign("module",$module);
//$smarty -> assign("view",$view);
$smarty -> assign("siUrl",$siUrl);//used for template css
$smarty -> assign("LANG",$LANG);
$smarty -> assign("enabled",array($LANG['disabled'],$LANG['enabled']));

if (file_exists(dirname(__FILE__)."/../../templates/default/header.tpl"))
	$smarty -> display(dirname(__FILE__)."/../../templates/default/header.tpl");
else
	$smarty -> display(dirname(__FILE__)."/../../../../templates/default/header.tpl");

if (file_exists(dirname(__FILE__)."/../../templates/default/main.tpl"))
	$smarty -> display(dirname(__FILE__)."/../../templates/default/main.tpl");
else
	$smarty -> display(dirname(__FILE__)."/../../../../templates/default/main.tpl");

//$smarty -> display("./templates/default/iframe_customers/add.tpl");
//$smarty -> display("../templates/default/iframe_customers/add.tpl");
//$smarty -> display("../../templates/default/iframe_customers/add.tpl");
//$smarty -> display("../../../templates/default/iframe_customers/add.tpl");
//$smarty -> display("../../../../templates/default/iframe_customers/add.tpl");
$smarty -> display(dirname(__FILE__)."/../../templates/default/iframe_customers/add.tpl");
chdir($cwd);

