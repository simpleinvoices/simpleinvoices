<?php
global $smarty;

if (isset($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['invoice_id'])) {
    try {
        $saved = "false";
        if (Cron::update()) $saved = "true";
    } catch (PDOException $pde) {
        error_log("cron edit.php - Update error: " . $pde->getMessage());
    }
    $smarty->assign('saved', $saved);
}

$invoice_all = Invoice::select_all('no_age', 'id', "", "", "", "", "");

$cron = Cron::select();

$smarty->assign('invoice_all', $invoice_all);
$smarty->assign('cron'       , $cron);
$smarty->assign("domain_id"  , domain_id::get());

$smarty->assign('pageActive'   , 'cron');
$smarty->assign('subPageActive', 'cron_edit');
$smarty->assign('active_tab'   , '#money');

