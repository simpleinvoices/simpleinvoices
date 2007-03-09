<?php
include('./include/include_auth.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

include_once('./config/config.php');
include_once('./include/functions.php');
ob_start();
include_once("./lang/$language.inc.php");
ob_end_clean();

?>
