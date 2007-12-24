<?php

//TODO: have to be moved to an other place...
require_once("./modules/include/js/lgplus/php/chklang.php");
require_once("./modules/include/js/lgplus/php/settings.php");


$path = pathinfo($_SERVER['REQUEST_URI']);
$install_path = $path['dirname'];


include_once('./config/config.php');

include_once("./include/sql_queries.php");

include_once('./include/language.php');

include_once('./include/functions.php');

checkConnection();

include('./include/include_auth.php');
include_once('./include/manageCustomFields.php');
include_once("./include/validation.php");

?>
