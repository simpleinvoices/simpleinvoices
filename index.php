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

$module = isset($_GET['module'])?$_GET['module']:null;
$view = isset($_GET['view'])?$_GET['view']:null;
$action = isset($_GET['case'])?$_GET['case']:null;

/*
* The include configs and requirements stuff section - start
*/
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
$file = isset($file)?$file: "home";


//if auth - make sure is valid session else skip
// Check for any unapplied SQL patches when going home
if (($module == "options") && ($view == "database_sqlpatches")) {
	include_once('./modules/options/database_sqlpatches.php');
	donePatches();
} elseif ($file == 'home') {
	
	//if auth on must login before upgrade
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

	if( (strstr($view,"xml") OR (strstr($view,"ajax")) ) )
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


$path = "$module/$view";

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
					$smarty -> display("../extensions/$extension->name/templates/default/header.tpl");
					$extensionHeader++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionHeader == 0) 
		{
			$smarty -> display("../templates/default/header.tpl");
		}
		
}
/*
* Prep the page - load the header stuff - end
*/


/*
* Include the php file for the requested page section - start
*/

		/*Local the requested php file for the module*/
		preg_match("/^[a-z|A-Z|_]+\/[a-z|A-Z|_]+/",$path,$res);

		if(isset($res[0]) && $res[0] == $path) 
		{
			$file = $path;
		}	
		
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
				if(file_exists("./extensions/$extension->name/modules/$path.php")) {
			
					preg_match("/^[a-z|A-Z|_]+\/[a-z|A-Z|_]+/",$path,$res);

					if(isset($res[0]) && $res[0] == $path) {
						$file = $path;
					}	

					include_once("./extensions/$extension->name/modules/$file.php");
					$extensionPHPFile++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if( ($extensionPHPFile == 0) AND (file_exists("./modules/$file.php")) ) 
		{
			include_once("./modules/$file.php");
		}

/*
* Include the php file for the requested page section - end
*/

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
					$smarty -> display("../extensions/$extension->name/include/jquery/$extension->name.post_load.jquery.ext.js.tpl");
			}
		}
		
	}
	/*
	* If no extension php file for requested file load the normal php file if it exists
	*/
	if($extensionPostLoadJquery == 0) 
	{
		$smarty -> display("../include/jquery/post_load.jquery.ext.js.tpl");
	}

/*
* If extension is enabled load its javascript files	- end
*/
		
		
		
if($module == "export")
{
//	exit(0);
}	

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
					$smarty -> display("../extensions/$extension->name/templates/default/menu.tpl");
					$extensionMenu++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionMenu == "0") 
		{
			$smarty -> display("../templates/default/menu.tpl");
		}
	}
/*
* Menu : If extension has custom menu use it else use default - end
*/


/*
* Main : If extension has custom layout use it else use default - start
*/

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
					$smarty -> display("../extensions/$extension->name/templates/default/main.tpl");
					$extensionMain++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionMain == "0") 
		{
			$smarty -> display("../templates/default/main.tpl");
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
			if(file_exists("./extensions/$extension->name/templates/default/$file.tpl")) 
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
					$smarty -> display("../extensions/$extension->name/templates/default/footer.tpl");
					$extensionFooter++;
				}
			}
		}
		/*
		* If no extension php file for requested file load the normal php file if it exists
		*/
		if($extensionFooter == 0) 
		{
			$smarty -> display("../templates/default/footer.tpl");
		}	
	}
/*
* Footer - end 
*/
