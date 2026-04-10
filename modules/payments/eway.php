<?php

$saved = false;

$invoiceobj = new invoice();
$invoice_all = $invoiceobj->get_all();

if ( ($_POST['op'] =='add') AND (!empty($_POST['invoice_id'])) )
{

	$invoice = $invoiceobj->select($_POST['invoice_id']);

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

$bladeView -> assign('invoice_all',$invoice_all);
$bladeView -> assign('saved',$saved);

$bladeView -> assign('pageActive', 'payment');
$bladeView -> assign('subPageActive', 'payment_eway');
$bladeView -> assign('active_tab', '#money');

