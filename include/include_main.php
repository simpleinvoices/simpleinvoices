<?php
include('./include/auth/auth.php');
include_once('./config/config.php');
include_once('./include/functions.php');
ob_start();
include_once("./lang/$language.inc.php");
ob_end_clean();
include_once('./include/menu.php');

?>
