<?php

checkLogin();

# Deal with op and add some basic sanity checking

error_log($_POST['name']."  ".$_POST['value']);

$saved = false;

if (isset($_POST['op']) && $_POST['op'] == 'update_system_defaults' ) {
    $system_defaults = new SimpleInvoices_SystemDefaults();
    $saved = $system_defaults->update($_POST['name'], $_POST['value']);
}
$smarty -> assign("saved",$saved);

$smarty -> assign('pageActive', 'system_default');
$smarty -> assign('active_tab', '#setting');
