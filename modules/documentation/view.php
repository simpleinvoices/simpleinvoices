<?php

$menu = false;

$get_page = $_GET['page'];

$page = isset($LANG[$get_page]) ? $LANG[$get_page] :  $LANG['no_help_page'] ;


$smarty -> assign("page",$page);


?>
