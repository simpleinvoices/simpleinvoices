<?php

$saved = "false";
if (isset($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['invoice_id'])) {
    $result = cron::insert();
    $saved = !empty($result) ? "true" : "false";
}      

$cron = cron::select();

$smarty -> assign('saved',$saved);
$smarty -> assign('cron',$cron);
$smarty -> assign('pageActive', 'cron');
$smarty -> assign('subPageActive', 'cron_view');
$smarty -> assign('active_tab', '#money');
