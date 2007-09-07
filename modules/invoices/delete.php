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

$invoiceItems = getInvoiceItems($invoice_id);

$pageActive = "invoices";

$smarty -> assign('pageActive', $pageActive);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("preference",$preference);

$smarty -> assign("defaults",$defaults);
$smarty -> assign("invoicePaid",$invoicePaid);
$smarty -> assign("invoiceItems",$invoiceItems);

/*If delete is disabled - dont allow people to view this page*/
if ( $defaults['delete'] == 'N' ) {
	die('Invoice deletion has been disabled, you are not supposed to be here');
}


if ( ($_GET['stage'] == 2 ) AND ($_POST['doDelete'] == 'y') ) {
	
	//TODO - need to wrap the both deletes in a sql transaction
	//delete products from producsts table for total style
	if ($invoice['type_id'] == 1) 
	{
		delete('products','id',$invoiceItems['0']['product']['id']);
	}
	//delete the info from the invoice table and the invoice items table
	delete('invoices','id',$invoice_id);
	delete('invoice_items','invoice_id',$invoice_id);
	//TODO - what about the stuff in the products table for the total style invoices?
	echo "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=invoices&view=manage>";

}




?>
