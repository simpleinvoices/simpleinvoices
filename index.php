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
$smarty -> assign("LANG",$LANG);



/*dont include the header if requested file is an invoice template - for print preview etc.. header is not needed */
if (($module == "invoices" ) AND (strstr($view,"templates"))) {
	if (file_exists("./modules/invoices/template.php")) {
	        include("./modules/invoices/template.php");
	}
	else {
		echo "The file that you requested doesn't exist";
	}
}
else if (($module != null ) AND ($view != null)) {

	$smarty -> display("../templates/default/header.tpl");
	
	/*Check to make sure that the requested files exist*/
	if (file_exists("./modules/$module/$view.php")) {

			
			if(file_exists("./templates/default/{$module}/{$view}.tpl")) {
				include("./modules/$module/$view.php");
				
				$smarty -> display("../templates/default/{$module}/{$view}.tpl");
			}
			else {
	        	include("./modules/$module/$view.php");
			}
	}
	else {
		echo "The file that you requested doesn't exist";
	}
	
	$smarty -> display("../templates/default/footer.tpl");
}
/*If all else fails show the start.php page */
else {
        $smarty -> display("../templates/default/header.tpl");
        include("./modules/home.php");			
		$smarty -> display("../templates/default/home.tpl");
        $smarty -> display("../templates/default/footer.tpl");
}
?>
