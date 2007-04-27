<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/* validataion code */
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("c_name",$LANG['customer_name']);
jsFormValidationEnd();
jsEnd();
/* end validataion code */


#get the invoice id
$customer_id = $_GET['submit'];

$customer = getCustomer($customer_id);

$wording_for_enabled = $customer['c_enabled'] == 1 ?$LANG['enabled']:$LANG['disabled'];


$invoice_total_Field = calc_customer_total($customer['c_id']);
$invoice_total_Field_formatted = number_format($invoice_total_Field,2);
#invoice total calc - end

#amount paid calc - start
$invoice_paid_Field = calc_customer_paid($customer['c_id']);;
$invoice_paid_Field_formatted = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
$invoice_owing_Field = number_format($invoice_total_Field - $invoice_paid_Field,2);
#get custom field labels

$customFieldLabel = getCustomFieldLabels("customer");

#show invoices per client
$sql = "SELECT * FROM {$tb_prefix}invoices WHERE inv_customer_id =$customer_id  ORDER BY inv_id desc";

//$customFieldLabel = getCustomFieldLabels("biller");

$display_block = "";
$footer = "";

include('./templates/default/customers/details2.tpl');

if ($_GET['action'] == "view") {
	$display_block = $display_block_view;
	include('./src/invoices/manage.inc.php');
	$display_block .= $display_block_view2;
	$footer = $footer_view;
}
else if ($_GET['action'] == "edit") {
	$display_block = $display_block_edit;
	$footer = $footer_edit;
}

include('./templates/default/customers/details2.tpl');

echo $block;
?>
