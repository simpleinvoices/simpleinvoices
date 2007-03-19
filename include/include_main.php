<?php


require_once("./src/include/js/lgplus/php/chklang.php");
require_once("./src/include/js/lgplus/php/settings.php");


include('./include/include_auth.php');
include_once('./config/config.php');
include_once('./include/functions.php');
ob_start();
include_once("./lang/$language.inc.php");
ob_end_clean();

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


?>
