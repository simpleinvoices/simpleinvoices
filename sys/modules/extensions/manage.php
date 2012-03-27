<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

isset($_GET['id']) && $extension_id = $_GET['id'];
isset($_GET['action']) && $action = $_GET['action'];

$SI_EXTENSIONS = new SimpleInvoices_Db_Table_Extensions();
if ($action == 'toggle') {
    $SI_EXTENSIONS->toggleStatus($extension_id) or die(htmlsafe("Something went wrong with the status change!"));
}

$smarty -> assign("exts",$SI_EXTENSIONS->getCount());

$smarty -> assign('pageActive', 'setting');
$smarty -> assign('active_tab', '#setting');
$smarty -> assign('subPageActive', 'setting_extensions');
