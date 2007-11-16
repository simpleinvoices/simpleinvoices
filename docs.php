<?php

/*
* Script: login.php
* 	Login page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
$t = isset($_GET['t'])?$_GET['t']:null;
$p = isset($_GET['p'])?$_GET['p']:null;

require_once("./include/smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty -> compile_dir = "./cache/";
include("./include/include_main.php");

$smarty -> assign("LANG",$LANG);

if(isset($_GET['lang'])) {
	$lang = $_GET['lang'];
}
else {
	$lang = "en";
}

/*Check the $view for validitity - make sure no ones hacking the url */
if (!ereg("^[a-zA-Z_#&0-9]+$",$p)) {
        die("Invalid view requested");
}


if($_GET['t'] == "help") {
	
	if(file_exists("./documentation/$language/help/$_GET[p].html"))
	{
		include("./documentation/$language/help/$_GET[p].html");
	}
	if(!file_exists("./documentation/$language/help/$_GET[p].html"))
	{
		include("./documentation/en-gb/help/$_GET[p].html");
	}
}
else {

	$file = "./documentation/$language/general/$_GET[p].html";
	
	if(!file_exists($file)) {
		$file = "./documentation/en-gb/general/$_GET[p].html";
	}
	
	//echo $file;
	$smarty -> display("../templates/default/header.tpl");
	$smarty -> display("../templates/default/menu.tpl");
	$smarty -> display("../templates/default/main.tpl"); 
	include($file);
	$smarty -> display("../templates/default/footer.tpl");

}

?>
