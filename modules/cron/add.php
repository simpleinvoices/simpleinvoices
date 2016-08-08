<?php
$saved = null;
if (!empty($_POST['op']) && $_POST['op'] =='add' && !empty($_POST['invoice_id'])) {
    $result = cron::insert();
    $saved = !empty($result) ? "true" : "false";
}

$invoices = new invoice();
$invoices->sort='id';
$invoice_all = $invoices->select_all('count');

$smarty->assign('invoice_all'  , $invoice_all);
$smarty->assign('saved'        , $saved);

$smarty->assign('pageActive'   , 'cron');
$smarty->assign('subPageActive', 'cron_add');
$smarty->assign('active_tab'   , '#money');
