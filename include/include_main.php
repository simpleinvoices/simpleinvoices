<?php


require_once("./modules/include/js/lgplus/php/chklang.php");
require_once("./modules/include/js/lgplus/php/settings.php");


$path = pathinfo($_SERVER['REQUEST_URI']);
$install_path = $path['dirname'];

include_once('./include/language.php');

include_once('./config/config.php');

include("./include/sql_queries.php");

include('./modules/options/database_sqlpatches.php');


if(getNumberOfPatches() == 0 ) {
	include('./include/include_auth.php');
}


include_once('./include/functions.php');

include_once("./include/validation.php");

?>
