<?php
global $LANG, $smarty;

$menu = false;
if ($menu) {} // eliminates unused warning

if (isset($_GET['help'])) {
    $page = $_GET['help'];
}
else {
    $get_page = $_GET['page'];
    $page = isset($LANG[$get_page]) ? $LANG[$get_page] : $LANG['no_help_page'];
}

$smarty->assign("page", $page);
