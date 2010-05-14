<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$maxInvoice = maxInvoice();

jsBegin();
jsFormValidationBegin("frmpost");
#jsValidateifNum("ac_inv_id",$LANG['invoice_id']);
#jsPaymentValidation("ac_inv_id",$LANG['invoice_id'],1,$maxInvoice['maxId']);
jsValidateifNum("ac_amount",$LANG['amount']);
jsValidateifNum("ac_date",$LANG['date']);
jsFormValidationEnd();
jsEnd();


/* end validataion code */

$today = date("Y-m-d");

$master_invoice_id = $_GET['id'];
$invoice = null;

if(isset($_GET['id'])) {
	$invoice = invoice::select($master_invoice_id);
}
else {
	$sth = dbQuery("SELECT * FROM ".TB_PREFIX."invoices");
    $invoice = $sth->fetch();
    #$sth = new invoice();
    #$invoice = $sth->select_all();
}
$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);
$defaults = getSystemDefaults();
$pt = getPaymentType($defaults['payment_type']);

$invoices = new invoice();
$invoices->sort='id';
$invoices->having='money_owed';
$invoice_all = $invoices->select_all('count');

$smarty -> assign('invoice_all',$invoice_all);
$paymentTypes = getActivePaymentTypes();

$smarty -> assign("paymentTypes",$paymentTypes);
$smarty -> assign("defaults",$defaults);
$smarty -> assign("biller",$biller);
$smarty -> assign("customer",$customer);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("today",$today);

$smarty -> assign('pageActive', 'payment');
$subPageActive =  "payment_process" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#money');
?>
