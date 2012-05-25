<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
$SI_PAYMENT_TYPES = new SimpleInvoices_Db_Table_PaymentTypes();
$SI_BILLER = new SimpleInvoices_Db_Table_Biller();

//$maxInvoice = maxInvoice();

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

$invoice = null;

if(!isset($_GET['id'])) {
	throw new SimpleInvoices_Exception('Invalid invoice identifier.');
}
//$invoice = invoice::select($_GET['id']);
$invoice = new SimpleInvoices_Invoice($_GET['id']);
	
// NOTE: Doesn't this mess up payments?
//else {
//	$sth = dbQuery("SELECT * FROM ".TB_PREFIX."invoices");
//    $invoice = $sth->fetch();
//    #$sth = new invoice();
//    #$invoice = $sth->select_all();
//}

$customer = $invoice->getCustomer();
$biller = $invoice->getBiller();

$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();
$pt = $SI_PAYMENT_TYPES->getPaymentTypeById($defaults['payment_type']);

$invoices = new invoice();
$invoices->sort='id';
$invoices->having='money_owed';
$invoice_all = $invoices->select_all('count');

$smarty -> assign('invoice_all',$invoice_all);
$paymentTypes = $SI_PAYMENT_TYPES->fetchAllActive();

$smarty -> assign("paymentTypes",$paymentTypes);
$smarty -> assign("defaults",$defaults);
$smarty -> assign("biller",$biller->toArray());
$smarty -> assign("customer",$customer->toArray());
$smarty -> assign("invoice",$invoice->toArray());
$smarty -> assign("today",$today);

$smarty -> assign('pageActive', 'payment');
$subPageActive =  "payment_process" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#money');
?>