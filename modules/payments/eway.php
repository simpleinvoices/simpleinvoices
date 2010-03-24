<?php

$saved = false;

if ($_POST['op'] =='add' AND !empty($_POST['invoice_id']))
{

    $invoice= invoice::select($_POST['invoice_id']);
    //do eway payment
    $eway = new eway();
    $eway->invoice = $invoice;
    $saved = $eway->payment();  

}      

$invoice_all = invoice::get_all();

$smarty -> assign('invoice_all',$invoice_all);
$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'payments');
$smarty -> assign('subPageActive', 'payments_add');
$smarty -> assign('active_tab', '#money');

