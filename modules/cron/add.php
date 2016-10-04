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

$invoices = new Invoice();
$invoices->sort='id';
$invoice_all = $invoices->select_all('count');

$smarty->assign('invoice_all', $invoice_all);
$smarty->assign("domain_id"  , domain_id::get());

$smarty->assign('pageActive'   , 'cron');
$smarty->assign('subPageActive', 'cron_add');
$smarty->assign('active_tab'   , '#money');
