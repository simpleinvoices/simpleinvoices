<?php
//commented out by thehandcoder(Ben Brown) should be romoved oncethe new grid is done

//TODO: have to be moved to an other place...
//require_once("./modules/include/js/lgplus/php/chklang.php");
//require_once("./modules/include/js/lgplus/php/settings.php");


$path = pathinfo($_SERVER['REQUEST_URI']);
$install_path = $path['dirname'];


include_once('./config/config.php');

include("./include/sql_queries.php");

include_once('./include/language.php');

include_once('./include/functions.php');

checkConnection();

include('./modules/options/database_sqlpatches.php');

//dont undertand this one!!
//TODO - think more about this one
if(getNumberOfPatches() > 0 ) {
	include('./include/include_auth.php');
}

include_once('./include/manageCustomFields.php');
include_once("./include/validation.php");

?>
