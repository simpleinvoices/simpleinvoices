<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


/* validataion code */



#get max invoice id for validataion - start
$sql_max = "SELECT max(inv_id) as max_inv_id FROM {$tb_prefix}invoices";
$result_max = mysql_query($sql_max, $conn) or die(mysql_error());

while ($max_invoice = mysql_fetch_array($result_max) ) {
	/*
	$max_invoice_id = $max_invoice['max_inv_id'];
	*/
};

#get max invoice id for validataion - end

jsBegin();
jsFormValidationBegin("frmpost");
#jsValidateifNum("ac_inv_id",$LANG['invoice_id']);
jsPaymentValidation("ac_inv_id",$LANG['invoice_id'],1,$max_invoice['max_inv_id']);
jsValidateifNum("ac_amount",$LANG['amount']);
jsValidateifNum("ac_date",$LANG['date']);
jsFormValidationEnd();
jsEnd();

/* end validataion code */

$today = date("Y-m-d");

$master_invoice_id = $_GET['submit'];

#master invoice id select
if (!empty($master_invoice_id)) {
	$print_master_invoice_id = "SELECT * FROM {$tb_prefix}invoices WHERE inv_id = " . $master_invoice_id;
}
elseif (empty($master_invoice_id)) {
	$print_master_invoice_id = "SELECT * FROM {$tb_prefix}invoices";
}
$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

$inv = mysql_fetch_array($result_print_master_invoice_id);

#customer query
$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE c_id = $inv[inv_customer_id]";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

$customer = mysql_fetch_array($result_print_customer);

#biller query
$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE id = $inv[inv_biller_id]";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());

$biller = mysql_fetch_array($result_print_biller);

#biller query
$sql = "SELECT * FROM {$tb_prefix}payment_types where pt_enabled != 0 ORDER BY pt_description";
$result = mysql_query($sql, $conn) or die(mysql_error());


#DEFAULTS
#defaults query and DEFAULT NUMBER OF LINE ITEMS
/*$sql_defaults = "SELECT * FROM {$tb_prefix}defaults";
$result_defaults = mysql_query($sql_defaults, $conn) or die(mysql_error());

$default = mysql_fetch_array($result_defaults);*/

$defaults = getSystemDefaults();

#Get the names of the defaults from their id -start
#default biller name query
$sql_payment_type_default = "SELECT pt_description FROM {$tb_prefix}payment_types where pt_id = $defaults[payment_type]";
$result_payment_type_default = mysql_query($sql_payment_type_default , $conn) or die(mysql_error());

$pt = mysql_fetch_array($result_payment_type_default);


#biller selector

if (mysql_num_rows($result) == 0) {
	//no records
	$display_block_payment_type = "<p><em>{$LANG['no_payment_types']}</em></p>";

} else {
	//has records, so display them
	$display_block_payment_type = <<<EOD
<select name="ac_payment_type">
<option selected value="{$defaults['payment_type']}" style="font-weight: bold">{$pt['pt_description']}</option>
EOD;

	while ($recs = mysql_fetch_array($result)) {
		/*
		$id = $recs['pt_id'];
		$display_name = $recs['pt_description'];
		*/
		$display_block_payment_type .= <<<EOD
	<option value="{$recs['pt_id']}">
		{$recs['pt_description']}</option>
EOD;
}
}


#Accounts - for the invoice - start
#invoice total calc - start
$invoice_total_Field = calc_invoice_total($inv['inv_id']);
$invoice_total_Field_formatted = number_format($invoice_total_Field,2);
#invoice total calc - end

#amount paid calc - start
$invoice_paid_Field = calc_invoice_paid($inv['inv_id']);
$invoice_paid_Field_formatted = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
$invoice_owing_Field = $invoice_total_Field - $invoice_paid_Field;
$invoice_owing_Field_formatted = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end
#Accounts - for the invoice - end

# Deal with op and add some basic sanity checking

$op = !empty( $_GET['op'] ) ? addslashes( $_GET['op'] ) : NULL;

if ($op === "pay_selected_invoice") {

	$display_block = <<<EOD
<table align="center">	
<tr>
	<td class="details_screen">{$LANG['invoice_id']}</td>
	<td><input type="hidden" name="ac_inv_id" value="{$inv['inv_id']}" />{$inv['inv_id']}</td>
	<td class="details_screen">{$LANG['total']}</td><td>{$invoice_total_Field_formatted}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG['biller']}</td>
	<td>{$biller['name']}</td>
	<td class="details_screen">{$LANG['paid']}</td>
	<td>{$invoice_paid_Field_formatted}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG['customer']}</td>
	<td>{$customer['c_name']}</td>
	<td class="details_screen">{$LANG['owing']}</td>
	<td><u>{$invoice_owing_Field_formatted}</u></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['amount']}</td>
	<td colspan="5"><input type="text" name="ac_amount" size="25" value="{$invoice_owing_Field}" /><a href="docs.php?t=help&p=process_payment_auto_amount." rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['date_formatted']}</td>
	<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today}" /></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['payment_type_method']}</td>
	<td>{$display_block_payment_type}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG['note']}</td>
	<td colspan="5"><textarea name="ac_notes" rows="5" cols="50"></textarea></td>
</tr>
</table>

EOD;

	$insert_action_op = "pay_selected_invoice";

}
/*Code for the when the user want to process a payment and manually enter the invoice id ie, not come from print_preview - come from Process Payment menu item */
else if ($op === "pay_invoice") {
	$display_block = <<<EOD


<table align="center">
<tr>
	<td class="details_screen">{$LANG['invoice_id']}
	<a href="docs.php?t=help&p=process_payment_inv_id" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type="text" id="ac_me" name="ac_inv_id" /></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['details']}
	<a href="docs.php?t=help&p=process_payment_details" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td id="js_total"><i>{$LANG['select_invoice']}</i> </td>
</tr>
<tr>
	<td class="details_screen">{$LANG['amount']}</td>
	<td colspan="5"><input type="text" name="ac_amount" size="25" /></td>
</tr>
<tr>
	<div class="demo-holder">
		<td class="details_screen">{$LANG['date_formatted']}</td>
		<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today}" /></td>
	</div>
</tr>
<tr>
	<td class="details_screen">{$LANG['payment_type_method']}</td>
	<td>{$display_block_payment_type}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG['note']}</td>
	<td colspan="5"><textarea name="ac_notes" rows="5" cols="50"></textarea></td>
</tr>
</table>

EOD;

	$insert_action_op = "pay_invoice";

}
else if ($op === "pay_invoice_batch") {
}

echo <<<EOD

<form name="frmpost" action="index.php?module=payments&view=save" method="post" onsubmit="return frmpost_Validator(this)">
<b>{$LANG['process_payment']}</b>
 <hr></hr>

$display_block
<hr></hr>
	<input type=submit name="process_payment" value="{$LANG['process_payment']}">
	<input type=hidden name="op" value="{$insert_action_op}">
</form>
EOD;
?>
