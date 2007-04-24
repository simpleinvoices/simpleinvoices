<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name","Biller name");
jsFormValidationEnd();
jsEnd();
/*end validation code*/


#biller query
$sql = "select {$tb_prefix}account_payments.*, {$tb_prefix}customers.c_name, {$tb_prefix}biller.b_name from {$tb_prefix}account_payments, {$tb_prefix}invoices, {$tb_prefix}customers, {$tb_prefix}biller  where ac_inv_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = {$tb_prefix}customers.c_id and {$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.b_id and {$tb_prefix}account_payments.id='$_GET[inv_id]'";


$result = mysql_query($sql, $conn) or die(mysql_error());

$stuff = mysql_fetch_array($result);
$stuff['date'] = date( $config['date_format'], strtotime( $stuff['ac_date'] ) );


/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$inv_type_sql = "SELECT * FROM {$tb_prefix}invoices WHERE inv_id = {$stuff['ac_inv_id']}";
$inv_type_result = mysql_query($inv_type_sql, $conn) or die(mysql_error());

$invoiceType = mysql_fetch_array($inv_type_result);


$sql_invoice_desc = "SELECT inv_ty_description FROM {$tb_prefix}invoice_type WHERE inv_ty_id = {$invoiceType['inv_type']}";
$result_invoice_desc = mysql_query($sql_invoice_desc, $conn) or die(mysql_error());


$invoiceDescription = mysql_fetch_array($result_invoice_desc);


/*Code to get the Invoice preference - so can link from this screen back to the invoice - END*/

#Payment type section
$payment_type_description = "select pt_description from {$tb_prefix}payment_types where pt_id = {$stuff['ac_payment_type']}";
$result_payment_type_description = mysql_query($payment_type_description, $conn) or die(mysql_error());

$paymentType = mysql_fetch_array($result_payment_type_description);



$display_block =  <<<EOD
<table align=center>
	<tr>
		<td class='details_screen'>$LANG['payment_id']</td><td>{$stuff['id']}</td>
	</tr>
	<tr>
		<td class='details_screen'>$LANG['invoice_id']</td><td><a href='print_quick_view.php?submit={$stuff['ac_inv_id']}&action=view&invoice_style={$invoiceDescription['inv_ty_description']}''>{$stuff['ac_inv_id']}</a></td>
	</tr>
	<tr>
		<td class='details_screen'>$LANG['amount']</td><td>{$stuff['ac_amount']}</td>
	</tr>
	<tr>
		<td class='details_screen'>$LANG['date_upper']</td><td>{$stuff['date']}</td>
	</tr>
	<tr>
		<td class='details_screen'>$LANG['biller']</td><td>{$stuff['b_name']}</td>
	</tr>
	<tr>
		<td class='details_screen'>$LANG['customer']</td><td>{$stuff['c_name']}</td>
	</tr>
	<tr>
		<td class='details_screen'>$LANG['payment_type']</td><td>{$paymentType['pt_description']}</td>
	</tr>
        <tr>
                <td class='details_screen'>$LANG['notes']</td><td>{$stuff['ac_notes']}
        </tr>

</table>
EOD;


echo <<<EOD
<b>$LANG[manage_payments']</b>
<hr></hr>

$display_block
<hr></hr>
	<form>
		<input type=button value='Back' onCLick='history.back()'>
	</form>
EOD;
?>
