<?php
//Stop direct browsing to this file
checkLogin();


$extension_id = $_GET['id'];
$extension_name = $_GET['name'];
$extension_desc = $_GET['description'];

if ($extension_id == null) {	// extension not yet registered

} else {

}

$smarty-> assign('id',$extension_id);
$smarty-> assign('name',$extension_name);
$smarty-> assign('description',$extension_desc);
$smarty-> assign('pageActive','extensions');
$smarty-> assign('active_tab','#settings');
?>
