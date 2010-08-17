<?php
/*
* Script: usedefault.php
* 	page which chooses an empty page or another invoice as templat
*
* Authors:
*	 Marcel van Dorp, Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2009-02-08
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

if ($_GET['action'] == 'update_template') {	/* update default template for customer */

 $sql = "UPDATE ".TB_PREFIX."customers SET custom_field4 = :cf4 WHERE id = :id";
 dbQuery($sql,
	':cf4', $_GET['id'],
 	':id', $master_customer_id
	);

 $smarty -> assign("view","quick_view");
 $smarty -> assign("spec","id");
 $smarty -> assign("id",$_GET['id']);
# print("debug=$sql");
} else {
 
$template = $defaults['default_invoice'];					/* GET DEFAULT TEMPLATE, OR NULL */
($customer['custom_field4'] != null) && $template = $customer['custom_field4'];	/* OVERRIDE WITH CF4 IF IT EXISTS */
$invoice = getInvoice($template);
$template = $invoice['id'];							/* CHECK IF TEMPLATE EXISTS, OR NULL */

 if ($template == null) { 				/* No template for this customer */
  $smarty -> assign("view","itemised");
  $smarty -> assign("spec","customer_id");
  $smarty -> assign("id",$master_customer_id);
 } else {						/* Use template for this customer */
  $smarty -> assign("view","details");
  $smarty -> assign("spec","template");
  $smarty -> assign("id",$template);
 }
}
?>
