<?php
global $smarty;

if (!empty($_POST['op']) && $_POST['op'] =='add' && !empty($_POST['invoice_id'])) {
    try {
        $saved = "false";
        if (Cron::insert()) $saved = "true";
    } catch (PDOException $pde) {
        error_log("cron add.php - insert error: " . $pde->getMessage());
    }
    $smarty->assign('saved'      , $saved);
}

$invoices = Invoice::select_all("no_age", "id", "", "", "", "", "");

$smarty->assign('invoice_all', $invoices);
$smarty->assign("domain_id"  , domain_id::get());

$smarty->assign('pageActive'   , 'cron');
$smarty->assign('subPageActive', 'cron_add');
$smarty->assign('active_tab'   , '#money');
