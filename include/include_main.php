<?php


require_once("./modules/include/js/lgplus/php/chklang.php");
require_once("./modules/include/js/lgplus/php/settings.php");

$path = pathinfo($_SERVER['REQUEST_URI']);
$install_path = $path['dirname'];

include('./include/include_auth.php');

//if (isset($_GET['location']) && $_GET['location'] === 'pdf' ) {
//	include('./include/include_auth.php');
//}



include_once('./include/functions.php');

include_once("./include/validation.php");

?>
