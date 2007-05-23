<?php

$t = isset($_GET['t'])?$_GET['t']:null;
$p = isset($_GET['p'])?$_GET['p']:null;

require_once("./include/smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty -> compile_dir = "./cache/";
include("./include/include_main.php");
include("./include/language.php");
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

	include("./documentation/$lang/help/$_GET[p].html");
}
else {

	$file = "./documentation/$lang/general/$_GET[p]";
	
	if(file_exists($file.".html")) {
		$file = $file.".html";
	}
	else {
		$file = $file.".php";
	}
	
	if(!file_exists($file)) {
		$file = "./documentation/en/general/about.php";
	}
	
	//echo $file;
	$smarty -> display("../templates/default/header.tpl");
	$smarty -> display("../templates/default/menu.tpl");
	$smarty -> display("../templates/default/main.tpl"); 
	include($file);
	$smarty -> display("../templates/default/footer.tpl");

}

?>
