<?php
$saved = "false";
if (isset($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['invoice_id'])) {
    $result = cron::update();
    $saved = !empty($result) ? "true" : "false";
}

$invoices = new invoice();
$invoices->sort='id';
$invoice_all = $invoices->select_all('count');

$cron = cron::select();

$smarty->assign('invoice_all',$invoice_all);
$smarty->assign('saved',$saved);
$smarty->assign('cron',$cron);

$smarty->assign('pageActive'   , 'cron');
$smarty->assign('subPageActive', 'cron_edit');
$smarty->assign('active_tab'   , '#money');
