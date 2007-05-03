<?php

$t = isset($_GET['t'])?$_GET['t']:null;
$p = isset($_GET['p'])?$_GET['p']:null;

/*require_once("./include/smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty -> compile_dir = "./cache/";
include("./include/include_main.php");
$smarty -> assign("LANG",$LANG);*/



if($_GET['t'] == "help") {
	include("./docs/en/help/$_GET[p].html");
}

?>
