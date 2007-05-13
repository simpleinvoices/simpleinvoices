<?php

//stop browsing to files directly - all viewing to be handled by index.php
//if browse not defined then the page will exit
define("BROWSE","browse");

$module = isset($_GET['module'])?$_GET['module']:null;
$view = isset($_GET['view'])?$_GET['view']:null;
$action = isset($_GET['case'])?$_GET['case']:null;

require_once("./include/smarty/Smarty.class.php");

$smarty = new Smarty();
$smarty -> compile_dir = "./cache/";

include("./include/include_main.php");
include('./modules/options/database_sqlpatches.php');


$smarty -> assign("LANG",$LANG);
//For Making easy enabled pop-menus (see biller)
$smarty -> assign("enabled",array($LANG['disabled'],$LANG['enabled']));

$menu = true;
$file = "home";


if(getNumberOfPatches() > 0 ) {
	$view = "database_sqlpatches";
	$module = "options";
	
	if($action == "run") {
		runPatches();
	}
	else {
		listPatches();
	}
	$menu = false;
}



/*dont include the header if requested file is an invoice template - for print preview etc.. header is not needed */
if (($module == "invoices" ) && (strstr($view,"templates"))) {
	//TODO: why is $view templates/template?...
	if (file_exists("./modules/invoices/template.php")) {
	        include("./modules/invoices/template.php");
	}
	else {
		echo "The file that you requested doesn't exist";
	}
	
	exit(0);
}


$path = "$module/$view";

if(file_exists("./modules/$path.php")) {
	
	preg_match("/^[a-z|_]+\/[a-z|_]+/",$path,$res);

	if(isset($res[0]) && $res[0] == $path) {
		$file = $path;
	}	
}



$smarty -> display("../templates/default/header.tpl");
if($menu) {
	$smarty -> display("../templates/default/menu.tpl");
}

$smarty -> display("../templates/default/main.tpl");

include_once("./modules/$file.php");

//Shouldn't be necessary anymore. Ist for old files without tempaltes...
if(file_exists("./templates/default/$file.tpl")) {
	$smarty -> display("../templates/default/$file.tpl");
}
else {
	echo "NOTEMPLATE!!";
}

$smarty -> display("../templates/default/footer.tpl");

?>
