<?php

/*
* Script: index.php
* 	Main controller file for Simple Invoices
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/

//stop browsing to files directly - all viewing to be handled by index.php
//if browse not defined then the page will exit
define("BROWSE","browse");

//keeps the old path
set_include_path(get_include_path() . PATH_SEPARATOR . "./include". PATH_SEPARATOR . "./library". PATH_SEPARATOR . "./include/smarty_plugins" );

$module = isset($_GET['module'])?$_GET['module']:null;
$view = isset($_GET['view'])?$_GET['view']:null;
$action = isset($_GET['case'])?$_GET['case']:null;

require_once("smarty/Smarty.class.php");
require_once("./include/functions.php");

$smarty = new Smarty();

//cache directory. Have to be writeable (chmod 777)
$smarty -> compile_dir = "cache";
if(!is_writable($smarty -> compile_dir)) {
	simpleInvoicesError("cache", $smarty -> compile_dir);
	//exit("Simple Invoices Error : The folder <i>".$smarty -> compile_dir."</i> has to be writeable");
}


//adds own smarty plugins
$smarty->plugins_dir = array("plugins","include/smarty_plugins");

/*
* The include configs and requirements stuff section - start
*/
require_once("./include/include_main.php");

foreach($extension as $key=>$value)
{
	/*
	* If extension is enabled then continue and include the requested file for that extension if it exists
	*/	
	if($value['enabled'] == "1")
	{
		//echo "Enabled:".$value['name']."<br><br>";
		if(file_exists("./extensions/$value[name]/include/include_main.php"))
		{
			require_once("../extensions/$value[name]/include/include_main.php");
		}
	}
}
/*
* The include configs and requirements stuff section - end
*/

$smarty -> assign("authenticationOn",$authenticationOn); // to toggle the login / logout button visibility in the menu
$smarty -> assign("module",$module);


$smarty -> assign("LANG",$LANG);
//For Making easy enabled pop-menus (see biller)
$smarty -> assign("enabled",array($LANG['disabled'],$LANG['enabled']));

$menu = true;
//$menu = false;
$file = "home";

// Check for any unapplied SQL patches when going home
if (($module == "options") && ($view == "database_sqlpatches")) {
	include_once('./modules/options/database_sqlpatches.php');
	donePatches();
} elseif ($file == 'home') {
	include_once('./modules/options/database_sqlpatches.php');
	if (getNumberOfPatches() > 0 ) {
		$view = "database_sqlpatches";
		$module = "options";
		
		if($action == "run") {
			runPatches();
		} else {
			listPatches();
		}
		$menu = false;
	}
}

/*
* dont include the header if requested file is an invoice template - for print preview etc.. header is not needed 
*/
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

/*
* Get the xml for the new grids
*/

if (strstr($view,"xml")) {
	if (file_exists("./modules/$module/xml.php")) {
		include("./modules/$module/xml.php");
	}
	else {
		echo "The file that you requested doesn't exist";
	}
	
	exit(0);

}

$path = "$module/$view";

/*
* Include the php file for the requested page section - start
*/

/*Local the requested php file for the module*/
if(file_exists("./modules/$path.php")) 
{
	preg_match("/^[a-z|A-Z|_]+\/[a-z|A-Z|_]+/",$path,$res);

	if(isset($res[0]) && $res[0] == $path) 
	{
		$file = $path;
	}	
}



$smarty -> display("../templates/default/header.tpl");
//temp added menu.tpl back in so we can easily design new menu system


include_once("./modules/$file.php");

/*
* If extension is enabled load the extension php file for the module	
* Note: this system is probably slow - if you got a better method for handling extensions let me know
*/
foreach($extension as $key=>$value)
{
	/*
	* If extension is enabled then continue and include the requested file for that extension if it exists
	*/	
	if($value['enabled'] == "1")
	{
		//echo "Enabled:".$value['name']."<br><br>";
		if(file_exists("./extensions/$value[name]/modules/$path.php")) {
	
			preg_match("/^[a-z|A-Z|_]+\/[a-z|A-Z|_]+/",$path,$res);

			if(isset($res[0]) && $res[0] == $path) {
				$file = $path;
			}	

			include_once("./extensions/$key[name]/modules/$file.php");
		}
	}
}

/*
* Include the php file for the requested page section - end
*/

//TODO - remove menu and main
//old main file no longer needed
if($menu) {
	//getMenuStructure();
	$smarty -> display("../templates/default/menu.tpl");
}

$smarty -> display("../templates/default/main.tpl");
//Shouldn't be necessary anymore. Ist for old files without tempaltes...


/*
* Include the smarty template for the requested page section - start
*/

/*
* If no extensions template is applicable then show the default one
* use the $extensionTemplates variable to count the number of applicable extensions template
* --if = 0 after checking all extensions then show default
*/
$extensionTemplates = 0;
foreach($extension as $tplKey=>$tplValue)
{
	/*
	* If extension is enabled then continue and include the requested file for that extension if it exists
	*/	
	if($tplValue['enabled'] == "1")
	{
		if(file_exists("./extensions/$tplValue[name]/templates/default/$file.tpl")) 
		{
			$path = "../extensions/$tplValue[name]/templates/default/$module/";
			$tplDirectory = "extensions/$tplValue[name]/";
			$extensionTemplates++;
		}
	}
}
/*
* If no application templates found then show default template
* TODO Note: if more than one extension has got a template for the requested file than thats trouble :(
* - we really need a better extensions system
*/
if( ($extensionTemplates == 0) AND (file_exists("./templates/default/$file.tpl")) ) 
{ 
			$path = "../templates/default/$module/";
			$tplDirectory = "";
			$extensionTemplates++;
}

$smarty->assign("path",$path);
$smarty -> display("../".$tplDirectory."templates/default/$file.tpl");

// If no smarty template - add message - onyl uncomment for dev - commented out for release
if ($extensionTemplates == 0 )
{
	error_log("NOTEMPLATE!!!");
}

/*
* Include the template for the requested page section - end
*/


// not needed
$smarty -> display("../templates/default/footer.tpl");


?>
