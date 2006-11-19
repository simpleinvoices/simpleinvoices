<?php
#include('./include/auth/auth.php');
include('./config/config.php');
include('./include/functions.php');
ob_start();
include("./lang/$language.inc.php");
ob_end_clean();
include('./include/menu.php');

?>
