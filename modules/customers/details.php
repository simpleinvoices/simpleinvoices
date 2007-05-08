<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#get the invoice id
$customer_id = $_GET['submit'];

$customer = getCustomer($customer_id);

$wording_for_enabled = $customer['enabled'] == 1 ?$LANG['enabled']:$LANG['disabled'];


$invoice_total_Field = calc_customer_total($customer['id']);
$invoice_total_Field_formatted = number_format($invoice_total_Field,2);
#invoice total calc - end

#amount paid calc - start
$invoice_paid_Field = calc_customer_paid($customer['id']);;
$invoice_paid_Field_formatted = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
$invoice_owing_Field = number_format($invoice_total_Field - $invoice_paid_Field,2);
#get custom field labels

$customFieldLabel = getCustomFieldLabels("customer");

#show invoices per client
$sql = "SELECT * FROM {$tb_prefix}invoices WHERE inv_customer_id =$customer_id  ORDER BY inv_id desc";

//$customFieldLabel = getCustomFieldLabels("biller");
$smarty -> assign('customer',$customer);
$smarty -> assign('customFieldLabel',$customFieldLabel);

?>
