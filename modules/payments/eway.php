<?php

$saved = false;

if ( ($_POST['op'] =='add') AND (!empty($_POST['invoice_id'])) )
{

    $invoice= invoice::select($_POST['invoice_id']);

    $eway_check = new eway();
    $eway_check->invoice = $invoice;
    $eway_pre_check = $eway_check->pre_check();
    
    if($eway_pre_check == 'true')
    {
        //do eway payment
        $eway = new eway();
        $eway->invoice = $invoice;
        $saved = $eway->payment();  
    } else {
        $saved = 'check_failed';
    }
    
}      

$invoice_all = invoice::get_all();

$smarty -> assign('invoice_all',$invoice_all);
$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('subPageActive', 'payment_eway');
$smarty -> assign('active_tab', '#money');

