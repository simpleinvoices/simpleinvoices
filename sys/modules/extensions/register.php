<?php
//Stop direct browsing to this file
checkLogin();


if (isset($_GET['id'])) $extension_id = $_GET['id'];
else $extension_id = NULL;

if (isset($_GET['name'])) $extension_name = $_GET['name'];
else $extension_name = NULL;

$action = $_GET['action'];

if (isset($_GET['description'])) $extension_desc = $_GET['description'];
else $extension_desc = NULL;



if ($extension_id == null) {	// extension not yet registered
    $count = 0;
} else {
    $extensions = new SimpleInvoices_Db_Table_Extensions();
    $info = $extensions->find($extension_id);
    $extension_name = $info['name'];
    $extension_desc = $info['description'];

    $system_defaults = new SimpleInvoices_Db_Table_SystemDefaults();
    $extension_defaults = $system_defaults->fetchAllForExtension($extension_id);
    $count = count($extension_defaults);
}

$smarty-> assign('id',$extension_id);
$smarty-> assign('action',$action);
$smarty-> assign('name',$extension_name);
$smarty-> assign('count',$count);
$smarty-> assign('description',$extension_desc);
$smarty-> assign('pageActive','extensions');
$smarty-> assign('active_tab','#settings');
?>
