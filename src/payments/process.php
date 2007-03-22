<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


/* validataion code */
include("./include/validation.php");



#get max invoice id for validataion - start
$sql_max = "SELECT max(inv_id) as max_inv_id FROM {$tb_prefix}invoices";
$result_max = mysql_query($sql_max, $conn) or die(mysql_error());

while ($Array_max = mysql_fetch_array($result_max) ) {
$max_invoice_id = $Array_max['max_inv_id'];
};

#get max invoice id for validataion - end

jsBegin();
jsFormValidationBegin("frmpost");
#jsValidateifNum("ac_inv_id",$LANG_invoice_id);
jsPaymentValidation("ac_inv_id",$LANG_invoice_id,1,$max_invoice_id);
jsValidateifNum("ac_amount",$LANG_amount);
jsValidateifNum("ac_date",$LANG_date);
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

while ($Array_master_invoice = mysql_fetch_array($result_print_master_invoice_id)) {
	$inv_idField = $Array_master_invoice['inv_id'];
	$inv_biller_idField = $Array_master_invoice['inv_biller_id'];
	$inv_customer_idField = $Array_master_invoice['inv_customer_id'];
	$inv_typeField = $Array_master_invoice['inv_type'];
	$inv_preferenceField = $Array_master_invoice['inv_preference'];
	$inv_dateField = date( $config['date_format'], strtotime( $Array_master_invoice['inv_date'] ) );
	$inv_noteField = $Array_master_invoice['inv_note'];
};

#customer query
$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE c_id = $inv_customer_idField";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_print_customer)) {
	$c_idField = $Array['c_id'];
	$c_attentionField = $Array['c_attention'];
	$c_nameField = $Array['c_name'];
	$c_street_addressField = $Array['c_street_address'];
	$c_cityField = $Array['c_city'];
	$c_stateField = $Array['c_state'];
	$c_zip_codeField = $Array['c_zip_code'];
	$c_countryField = $Array['c_country'];
	$c_phoneField = $Array['c_phone'];
	$c_faxField = $Array['c_fax'];
	$c_emailField = $Array['c_email'];
};

#biller query
$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE b_id = $inv_biller_idField";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());

while ($billerArray = mysql_fetch_array($result_print_biller)) {
	$b_idField = $billerArray['b_id'];
	$b_nameField = $billerArray['b_name'];
	$b_street_addressField = $billerArray['b_street_address'];
	$b_cityField = $billerArray['b_city'];
	$b_stateField = $billerArray['b_state'];
	$b_zip_codeField = $billerArray['b_zip_code'];
	$b_countryField = $billerArray['b_country'];
	$b_phoneField = $billerArray['b_phone'];
	$b_mobile_phoneField = $billerArray['b_mobile_phone'];
	$b_faxField = $billerArray['b_fax'];
	$b_emailField = $billerArray['b_email'];
};

#biller query
$sql = "SELECT * FROM {$tb_prefix}payment_types where pt_enabled != 0 ORDER BY pt_description";
$result = mysql_query($sql, $conn) or die(mysql_error());



#DEFAULTS
#defaults query and DEFAULT NUMBER OF LINE ITEMS
$sql_defaults = "SELECT * FROM {$tb_prefix}defaults";
$result_defaults = mysql_query($sql_defaults, $conn) or die(mysql_error());

while ($Array_defaults = mysql_fetch_array($result_defaults) ) {
$def_payment_typeField = $Array_defaults['def_payment_type'];
};

#Get the names of the defaults from their id -start
#default biller name query
$sql_payment_type_default = "SELECT pt_description FROM {$tb_prefix}payment_types where pt_id = $def_payment_typeField";
$result_payment_type_default = mysql_query($sql_payment_type_default , $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_payment_type_default) ) {
$sql_payment_type_desciptionField = $Array['pt_description'];
}



#biller selector

if (mysql_num_rows($result) == 0) {
//no records
$display_block_payment_type = "<p><em>{$LANG_no_payment_types}</em></p>";

} else {
//has records, so display them
$display_block_payment_type = <<<EOD
<select name="ac_payment_type">
<option selected value="$def_payment_typeField" style="font-weight: bold">$sql_payment_type_desciptionField</option>
EOD;

while ($recs = mysql_fetch_array($result)) {
	$id = $recs['pt_id'];
	$display_name = $recs['pt_description'];

	$display_block_payment_type .= <<<EOD
	<option value="$id">
		$display_name</option>
EOD;
}
}


#Accounts - for the invoice - start
#invoice total calc - start
	$invoice_total_Field = calc_invoice_total($inv_idField);
	$invoice_total_Field_formatted = number_format($invoice_total_Field,2);
#invoice total calc - end

#amount paid calc - start
		$invoice_paid_Field = calc_invoice_paid($inv_idField);
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
	<td class="details_screen">{$LANG_invoice_id}</td>
	<td><input type="hidden" name="ac_inv_id" value="{$inv_idField}" />{$inv_idField}</td>
	<td class="details_screen">{$LANG_total}</td><td>{$invoice_total_Field_formatted}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG_biller}</td>
	<td>{$b_nameField}</td>
	<td class="details_screen">{$LANG_paid}</td>
	<td>{$invoice_paid_Field_formatted}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG_customer}</td>
	<td>{$c_nameField}</td>
	<td class="details_screen">{$LANG_owing}</td>
	<td><u>{$invoice_owing_Field_formatted}</u></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_amount}</td>
	<td colspan="5"><input type="text" name="ac_amount" size="25" value="{$invoice_owing_Field}" /><a href="./src/documentation/info_pages/process_payment_auto_amount.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_date_formatted}</td>
	<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today}" /></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_payment_type_method}</td>
	<td>{$display_block_payment_type}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG_note}</td>
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
	<td class="details_screen">{$LANG_invoice_id}
	<a href="./src/documentation/info_pages/process_payment_inv_id.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type="text" id="ac_me" name="ac_inv_id" /></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_details}
	<a href="./src/documentation/info_pages/process_payment_details.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td id="js_total"><i>{$LANG_select_invoice}</i> </td>
</tr>
<tr>
	<td class="details_screen">{$LANG_amount}</td>
	<td colspan="5"><input type="text" name="ac_amount" size="25" /></td>
</tr>
<tr>
	<div class="demo-holder">
		<td class="details_screen">{$LANG_date_formatted}</td>
		<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today}" /></td>
	</div>
</tr>
<tr>
	<td class="details_screen">{$LANG_payment_type_method}</td>
	<td>{$display_block_payment_type}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG_note}</td>
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
<b>{$LANG_process_payment}</b>
 <hr></hr>

$display_block
<hr></hr>
	<input type=submit name="process_payment" value="{$LANG_process_payment}">
	<input type=hidden name="op" value="{$insert_action_op}">
EOD;
?>

</form>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
