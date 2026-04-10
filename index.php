<?php

/*
* Script: index.php
* 	Main controller file for Simple Invoices
*
* License:
*	 GPL v3 or above
*/

//minor change to test github emails - test

//stop browsing to files directly - all viewing to be handled by index.php
//if browse not defined then the page will exit
define("BROWSE","browse");


/*
* The include configs and requirements stuff section - start
*/

/*
* Load stuff required before init.php
*/
require_once("./include/init_pre.php");

$module = isset($_GET['module']) ? filenameEscape($_GET['module']) : null;
$view   = isset($_GET['view'])  ? filenameEscape($_GET['view'])    : null;
$action = isset($_GET['case'])  ? filenameEscape($_GET['case'])    : null;

require_once("./include/init.php");


/*
	GetCustomPath: override template or module with custom one if it exists, else return default path if it exists
	---------------------------------------------
	@name: name or dir/name of the module or template (without extension)
	@mode: template or module
*/

function GetCustomPath($name,$mode='template'){
	$my_custom_path="./custom/";
	$use_custom=1;
	$out = null; // Initialize return variable
	
	if($mode=='template'){
		if($use_custom and file_exists("{$my_custom_path}default_template/{$name}.blade.php")){
			$out="custom/default_template/{$name}.blade.php";
		}
		elseif(file_exists("./templates/default/{$name}.blade.php")){
			$out="templates/default/{$name}.blade.php";
		}
	}
	if($mode=='module'){
		if($use_custom and file_exists("{$my_custom_path}modules/{$name}.php")){
			$out="{$my_custom_path}modules/{$name}.php";
		}
		elseif(file_exists("./modules/{$name}.php")){
			$out="./modules/{$name}.php";
		}
	}
	return $out;
}
	

/*
* The include configs and requirements stuff section - end
*/

$bladeView -> assign("config",$config); // to toggle the login / logout button visibility in the menu
$bladeView -> assign("module",$module);
$bladeView -> assign("view",$view);
$bladeView -> assign("siUrl",$siUrl);//used for template css

$bladeView -> assign("LANG",$LANG);
//For Making easy enabled pop-menus (see biller)
$bladeView -> assign("enabled",array($LANG['disabled'],$LANG['enabled']));

/*
* Menu - hide or show menu
*/
$menu = isset($menu)?$menu: true;
/*
* File - set which page will be displayed as the start page
*/


//if auth - make sure is valid session else skip
// Check for any unapplied SQL patches when going home
//TODO - redo this code
if (($module == "options") && ($view == "database_sqlpatches")) {
	include_once('./include/sql_patches.php');
	donePatches();
} else {
	
	//check db structure - if only structure and no fields then prompt for imports
	// 1 import essential data
    $skip_db_patches = false;
	//$install_tables_exists = checkTableExists(TB_PREFIX."biller");
    if ( $install_tables_exists == false )
    { 
		$module="install";
		//$view="index";
		$view == "structure" ? $view ="structure" : $view="index";
        //do installer
        $skip_db_patches = true;
		
    }
	// Re-check essential data using same $db as installer (avoids connection/cache issues)
	if ( $install_tables_exists == true && ( !isset($install_data_exists) || $install_data_exists == false ) ) {
		$has_essential = false;
		try {
			$tables_to_check = array('custom_fields', 'preferences', 'sql_patchmanager', 'biller');
			foreach ($tables_to_check as $t) {
				$sth = @$db->query("SELECT 1 FROM " . TB_PREFIX . $t . " LIMIT 1");
				if ($sth && $sth->fetch()) {
					$has_essential = true;
					break;
				}
			}
			if ($has_essential) {
				$install_data_exists = true;
			}
		} catch (Exception $e) {
			// ignore
		}
	}
	if ( ($install_tables_exists == true) AND ($install_data_exists == false) )
    { 
	    $module = "install";
		$view == "essential" ? $view ="essential" : $view="structure";
		//$view = "essential";
        //do installer
        $skip_db_patches = true;
    }
    //count sql_patches
    // if 0 run import essential data
	// 2 import sample data
	//echo $skip_db_patches; 
	//if auth on must login before upgrade
    if ($skip_db_patches == false)
	{
		//var_dump($config->authentication->enable);
		if ( ($config->authentication->enabled == 1 AND isset($auth_session->id)) OR ($config->authentication->enabled == false) )	
		{
			include_once('./include/sql_patches.php');
			if (getNumberOfPatches() > 0 ) {
				$view = "database_sqlpatches";
				$module = "options";
				
				if($action == "run") {
					runPatches();
				} else {
					listPatches();
				}
				$menu = false;
			} else {
				//If no invoices in db then show home page as default - else show Manage Invoices page
				if ($module==null)
				{
					$invoiceobj = new invoice();
					if ( $invoiceobj->are_there_any() > "0" )  
					{
					    $module = "invoices" ;
						$view = "manage";
					
					} else { 
					    $module = "index" ;
						$view = "index";
					}
					unset($invoiceobj);
				}
			}
		}
    }

}


/*
* dont include the header if requested file is an invoice template - for print preview etc.. header is not needed 
*/

if (($module == "invoices" ) && (strstr($view,"template"))) {
		if ($my_path = GetCustomPath("invoices/template", 'module')) {
			include_once($my_path);
		}
		exit(0);
}

/*
* xml or ajax page requeset - start
*/

	if( strstr($module,"api") OR (strstr($view,"xml") OR (strstr($view,"ajax")) ) )
	{
		if ($my_path = GetCustomPath("$module/$view", 'module')) {
			include($my_path);
		}
		exit(0);
	}
/*
* xml or ajax page request - end
*/

$file= "$module/$view";

/*
* Prep the page - load the header stuff - start
*/

	// To remove the js error due to multiple document.ready.function() 
	// 	in jquery.datePicker.js, jquery.autocomplete.conf.js and jquery.accordian.js 
	//	 without instances in manage pages - Ap.Muthu
	/*
	* TODO: fix the javascript or move datapicker to extjs to fix this hack - not nice
	*/

/*
* Header - start
*/
if( !in_array($module."_".$view, $early_exit) )
{
		$bladeView->display(GetCustomPath('header'));
}
/*
* Prep the page - load the header stuff - end
*/


/*
* Include the php file for the requested page section - start
*/

	
		if ($my_path = GetCustomPath("$module/$view", 'module')) {
			include($my_path);
		}

/*
* Include the php file for the requested page section - end
*/
if($module == "export" OR $view == "export" OR $module == "api")
{
	exit(0);


}	

	if ($module != 'auth') {
		$bladeView->display("include/jquery/post_load_jquery_ext_js.blade.php");
	}
		
		
		

/*
* Menu : If extension has custom menu use it else use default - start
*/

	if ($menu == "true") {
		$bladeView->display(GetCustomPath('menu'));
	}
/*
* Menu : If extension has custom menu use it else use default - end
*/


	if ( !in_array($module."_".$view, $early_exit) ) {
		$bladeView->display(GetCustomPath('main'));
	}


/*
* Include the Blade view for the requested page section - start
*/

	$my_tpl_path = GetCustomPath("$module/$view");
	$path = $my_tpl_path ? dirname($my_tpl_path).'/' : '';
	$bladeView->assign("path", $path);
	
	// Debug and error handling for empty template path
	if (empty($my_tpl_path)) {
		error_log("ERROR: Empty template path for module='$module', view='$view'");
		error_log("GetCustomPath result: " . var_export(GetCustomPath("$module/$view"), true));
		error_log("Template path empty for this request");
		
		echo "<h2>Template Error</h2>";
		echo "<p>Unable to find template for module '<strong>$module</strong>' and view '<strong>$view</strong>'</p>";
		echo "<p>Checked paths:</p>";
		echo "<ul>";
		echo "<li>Custom: ./custom/default_template/$module/$view.blade.php</li>";
		echo "<li>Default: ./templates/default/$module/$view.blade.php</li>";
		echo "</ul>";
		
		// Try to provide helpful suggestion
		if (file_exists("./templates/default/$module/$view.blade.php")) {
			echo "<p><strong>Note:</strong> Default template exists but GetCustomPath didn't find it!</p>";
		}
		
		exit(1);
	}
	
	$bladeView -> display($my_tpl_path);
	
	// If no template path was found, error already logged above

/*
* Include the template for the requested page section - end
*/
	
/*
* Footer - start 
*/
	if ( !in_array($module."_".$view, $early_exit) ) {
		$bladeView->display(GetCustomPath('footer'));
	}

	
/*
* Footer - end 
*/



