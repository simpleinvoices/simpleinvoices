<?php
/*
* Script: usedefault.php
* 	page which chooses an empty page or another invoice as templat
*
* Authors:
*	 Marcel van Dorp, Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2009-02-07
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
#table

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$defaults = getSystemDefaults();
$master_customer_id = $_GET['customer_id'];
$customer = getCustomer($master_customer_id);

if ($customer['custom_field4'] == null) { 	/* No default template for this customer */
 #include('./extensions/default_invoice/modules/invoices/itemised.php'); 
 $smarty -> assign("view","itemised");
 $smarty -> assign("spec","customer_id");
 $smarty -> assign("id",$master_customer_id);
} else {
 $template = $customer['custom_field4'];
 #include('./extensions/default_invoice/modules/invoices/details.php'); 
 $smarty -> assign("view","details");
 $smarty -> assign("spec","template");
 $smarty -> assign("id",$template);
}

?>
