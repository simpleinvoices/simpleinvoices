<?php
/*
* Script: delete.php
* 	Do the deletion of an invoice page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-27
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
*/
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$invoice_id = $_GET['invoice'];

$invoice = getInvoice($invoice_id);
$preference = getPreference($invoice['preference_id']);

$defaults = getSystemDefaults();

$invoicePaid = calc_invoice_paid($invoice_id);



$smarty -> assign("invoice",$invoice);
$smarty -> assign("preference",$preference);

$smarty -> assign("defaults",$defaults);
$smarty -> assign("invoicePaid",$invoicePaid);

?>
