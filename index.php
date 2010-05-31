<?php

/*
* Script: index.php
* 	Main controller file for Simple Invoices
*
* License:
*	 GPL v3 or above
*/

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

foreach($config->extension as $extension)
{
	/*
	* If extension is enabled then continue and include the requested file for that extension if it exists
	*/	
	if($extension->enabled == "1")
	{
		//echo "Enabled:".$value['name']."<br><br>";
		if(file_exists("./extensions/$extension->name/include/init.php"))
		{
			require_once("./extensions/$extension->name/include/init.php");
		}
	}
}
/*
* The include configs and requirements stuff section - end
*/

$smarty -> assign("config",$config); // to toggle the login / logout button visibility in the menu
$smarty -> assign("module",$module);
$smarty -> assign("view",$view);
$smarty -> assign("siUrl",$siUrl);//used for template css

$smarty -> assign("LANG",$LANG);
//For Making easy enabled pop-menus (see biller)
$smarty -> assign("enabled",array($LANG['disabled'],$LANG['enabled']));

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
	include_once('./modules/options/database_sqlpatches.php');
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
		if ( ($config->authentication->enabled == 1 AND isset($auth_session->id)) OR ($config->authentication->enabled == 0) )	
		{
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
			} else {
				//If no invoices in db then show home page as default - else show Manage Invoices page
				if ($module==null)
				{
					if ( invoice::are_there_any() > "0" )  
					{
					    $module = "invoices" ;
						$view = "manage";
					
					} else { 
					    $module = "index" ;
						$view = "index";
					}
				}
			}
		}
    }

}


/*
* dont include the header if requested file is an invoice template - for print preview etc.. header is not needed 
*/

if (($module == "invoices" ) && (strstr($view,"template"))) {


		/*
		* If extension is enabled load the extension php file for the module	
		* Note: this system is probably slow - if you got a better method for handling extensions let me know
		*/
		$extensionInvoiceTemplateFile = 0;
		foreach($config->extension as $extension)
		{
			/*
			* If extension is enabled then continue and include the requested file for that extension if it exists
			*/	
			if($extension->enabled == "1")
			{
				//echo "Enabled:".$value['name']."<br><br>";
				if(file_exists("./extensions/$extension->name/modules/invoices/template.php")) {
			
					include_once("./extensions/$extension->name/modules/invoices/template.php");
					$extensionInvoiceTemplateFile++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if( ($extensionInvoiceTemplateFile == 0) AND (file_exists("./modules/invoices/template.php")) ) 
		{
			include_once("./modules/invoices/template.php");
		}


	exit(0);
}

/*
* xml or ajax page requeset - start
*/

	if( strstr($module,"api") OR (strstr($view,"xml") OR (strstr($view,"ajax")) ) )
	{	
		$extensionXml = 0;
		foreach($config->extension as $extension)
		{
			/*
			* If extension is enabled then continue and include the requested file for that extension if it exists
			*/	
			if($extension->enabled == "1")
			{
				if(file_exists("./extensions/$extension->name/modules/$module/$view.php")) 
				{
					include("./extensions/$extension->name/modules/$module/$view.php");
					$extensionXml++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionXml == 0) 
		{
			include("./modules/$module/$view.php");
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
* If extension is enabled load its javascript files	- start
* Note: this system is probably slow - if you got a better method for handling extensions let me know
*/
	$extension_jquery_files = "";
	
	foreach($config->extension as $extension)
	{
		/*
		* If extension is enabled then continue and include the requested file for that extension if it exists
		*/	
		if($extension->enabled == "1")
		{
			if(file_exists("./extensions/$extension->name/include/jquery/$extension->name.jquery.ext.js")) {
				$extension_jquery_files .= "<script type=\"text/javascript\" src=\"./extensions/$extension->name/include/jquery/$extension->name.jquery.ext.js\"></script>";
			}
		}
	}
	
	$smarty -> assign("extension_jquery_files",$extension_jquery_files);
/*
* If extension is enabled load its javascript files	- end
*/

/*
* Header - start 
*/
if( !in_array($module."_".$view, $early_exit) )
{
		$extensionHeader = 0;
		foreach($config->extension as $extension)
		{
			/*
			* If extension is enabled then continue and include the requested file for that extension if it exists
			*/	
			if($extension->enabled == "1")
			{
				if(file_exists("./extensions/$extension->name/templates/default/header.tpl")) 
				{
					$smarty -> $smarty_output("../extensions/$extension->name/templates/default/header.tpl");

					$extensionHeader++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionHeader == 0) 
		{
			$smarty -> $smarty_output("../templates/default/header.tpl");
		}
		
}
/*
* Prep the page - load the header stuff - end
*/


/*
* Include the php file for the requested page section - start
*/

	
		/*
		* If extension is enabled load the extension php file for the module	
		* Note: this system is probably slow - if you got a better method for handling extensions let me know
		*/
		$extensionPHPFile = 0;
		foreach($config->extension as $extension)
		{
			/*
			* If extension is enabled then continue and include the requested file for that extension if it exists
			*/	
			if($extension->enabled == "1")
			{


				//echo "Enabled:".$value['name']."<br><br>";
				if(file_exists("./extensions/$extension->name/modules/$module/$view.php")) {
			


					include_once("./extensions/$extension->name/modules/$module/$view.php");
					$extensionPHPFile++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if( ($extensionPHPFile == 0) AND (file_exists("./modules/$module/$view.php")) ) 
		{
			include_once("./modules/$module/$view.php");
		}

/*
* Include the php file for the requested page section - end
*/
if($module == "export" OR $view == "export" OR $module == "api")
{
	exit(0);


}	

/*
* If extension is enabled load its post load javascript files	- start
* By Post load - i mean post of the .php so that it can used info from the .php in the javascript
* Note: this system is probably slow - if you got a better method for handling extensions let me know
*/
	$extensionPostLoadJquery = 0;
	foreach($config->extension as $extension)
	{
		/*
		* If extension is enabled then continue and include the requested file for that extension if it exists
		*/	
		if($extension->enabled == "1")
		{
			if(file_exists("./extensions/$extension->name/include/jquery/$extension->name.post_load.jquery.ext.js.tpl")) {
					$smarty -> $smarty_output("../extensions/$extension->name/include/jquery/$extension->name.post_load.jquery.ext.js.tpl");
			}
		}
		
	}
	/*
	* If no extension php file for requested file load the normal php file if it exists
	* Don't load it in the authentication module. It's not needed! Generates wrong HTML code.
	*/
	if($extensionPostLoadJquery == 0 AND $module !='auth') 
	{
		$smarty -> $smarty_output("../include/jquery/post_load.jquery.ext.js.tpl");
	}

/*
* If extension is enabled load its javascript files	- end
*/
		
		
		

/*
* Menu : If extension has custom menu use it else use default - start
*/

	if($menu == "true")
	{	
		$extensionMenu = 0;
		foreach($config->extension as $extension)
		{
			/*
			* If extension is enabled then continue and include the requested file for that extension if it exists
			*/	
			if($extension->enabled == "1")
			{
				if(file_exists("./extensions/$extension->name/templates/default/menu.tpl")) 
				{
					$smarty -> $smarty_output("../extensions/$extension->name/templates/default/menu.tpl");
					$extensionMenu++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionMenu == "0") 
		{
			$smarty -> $smarty_output("../templates/default/menu.tpl");
		}
	}
/*
* Menu : If extension has custom menu use it else use default - end
*/


/*
* Main : If extension has custom layout use it else use default - start
*/

    if( !in_array($module."_".$view, $early_exit) )
    {
		$extensionMain = 0;
		foreach($config->extension as $extension)
		{
			/*
			* If extension is enabled then continue and include the requested file for that extension if it exists
			*/	
			if($extension->enabled == "1")
			{
				if(file_exists("./extensions/$extension->name/templates/default/main.tpl")) 
				{
					$smarty -> $smarty_output("../extensions/$extension->name/templates/default/main.tpl");
					$extensionMain++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionMain == "0") 
		{
			$smarty -> $smarty_output("../templates/default/main.tpl");
		}
    }
    
/*
* Main : If extension has custom menu use it else use default - end
*/


/*
* Include the smarty template for the requested page section - start
*/

	/*
	* If no extensions template is applicable then show the default one
	* use the $extensionTemplates variable to count the number of applicable extensions template
	* --if = 0 after checking all extensions then show default
	*/
	$extensionTemplates = 0;
	foreach($config->extension as $extension)
	{
		/*
		* If extension is enabled then continue and include the requested file for that extension if it exists
		*/	
		if($extension->enabled == "1")
		{
			if(file_exists("./extensions/$extension->name/templates/default/$module/$view.tpl")) 
			{
				$path = "../extensions/$extension->name/templates/default/$module/";
				$tplDirectory = "extensions/$extension->name/";
				$extensionTemplates++;
			}	
		}
	}
	/*
	* If no application templates found then show default template
	* TODO Note: if more than one extension has got a template for the requested file than thats trouble :(
	* - we really need a better extensions system
	*/
	if( ($extensionTemplates == 0) AND (file_exists("./templates/default/$module/$view.tpl")) ) 
	{ 
				$path = "../templates/default/$module/";
				$tplDirectory = "";
				$extensionTemplates++;
	}
	
	$smarty->assign("path",$path);
	$smarty -> $smarty_output("../".$tplDirectory."templates/default/$module/$view.tpl");
	
	// If no smarty template - add message - onyl uncomment for dev - commented out for release
	if ($extensionTemplates == 0 )
	{
		error_log("NOTEMPLATE!!!");
	}

/*
* Include the template for the requested page section - end
*/
	
/*
* Footer - start 
*/
	if( !in_array($module."_".$view, $early_exit) )
	{
		$extensionFooter = 0;
		foreach($config->extension as $extension)
		{
			/*
			* If extension is enabled then continue and include the requested file for that extension if it exists
			*/	
			if($extension->enabled == "1")
			{
				if(file_exists("./extensions/$extension->name/templates/default/footer.tpl")) 
				{
					$smarty -> $smarty_output("../extensions/$extension->name/templates/default/footer.tpl");
					$extensionFooter++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionFooter == 0) 
		{
			$smarty -> $smarty_output("../templates/default/footer.tpl");
		}
	
	}
	
	
/*
* Footer - end 
*/
