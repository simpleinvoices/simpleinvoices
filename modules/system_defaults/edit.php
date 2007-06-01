<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();




#system defaults query

$defaults = getSystemDefaults();

if ($_GET[submit] == "line_items") {

	jsBegin();
	jsFormValidationBegin("frmpost");
	jsValidateifNum("def_num_line_items","Default number of line items");
	jsFormValidationEnd();
	jsEnd();

	$default = "line_items";

		$display_block = <<<EOD
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['default_number_items']}</td>
		<td><input type=text size=25 name="value" value=$defaults[line_items]></td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
EOD;
}

else if ($_GET[submit] == "def_inv_template") {

	$default = "template";
	/*drop down list code for invoice template - only show the folder names in src/invoices/templates*/

	$handle=opendir("./templates/invoices/");
	while ($file = readdir($handle)) {
		if ($file != ".." && $file != "." && $file !="logos" && $file !=".svn" && $file !="template.php" && $file !="template.php~" ) {
			$files[] = $file;
		}
	}
	closedir($handle);

	sort($files);

	$display_block_templates_list = <<<EOD
	<select name="value">
EOD;

	$display_block_templates_list .= <<<EOD
	<option selected value='$defaults[template]' style="font-weight: bold" >$defaults[template]</option>
EOD;

	foreach ( $files as $var )
	{
		$display_block_templates_list .= "<option value='$var' >";
		$display_block_templates_list .= $var;
		$display_block_templates_list .= "</option>";
	}

	$display_block_templates_list .= "</select>";

	/*end drop down list section */
	/*start validataion section */

	jsBegin();
	jsFormValidationBegin("frmpost");
	jsValidateRequired("def_inv_template","{$LANG['default_inv_template']}");
	jsFormValidationEnd();
	jsEnd();
	/*end validataion section */

	/*$default = "def_inv_template";
	$def_inv_template = "select def_inv_template from {$tb_prefix}defaults where def_id=1";
	$result_def_inv_template = mysqlQuery($def_inv_template, $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result_def_inv_template) ) {
		$def_inv_templateField = $Array['def_inv_template'];*/


		$display_block = <<<EOD
	<tr>
		<td><br></td>
	</tr>
	<!--
	<tr>
		<td colspan=2><a href='text/default_invoice_template_text.html' class='lbOn'>Note</a></td>
	</tr>
	-->
	<tr>
		<td class="details_screen">{$LANG['default_inv_template']} <a href='docs.php?t=help&p=default_invoice_template_text' rel='gb_page_center[450, 450]'><img src="images/common/help-small.png"></img></a></td>
		<td>$display_block_templates_list</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
EOD;
	//}
}

else if ($_GET[submit] == "biller") {

	$default = "biller";

	#biller query
	$sql = "SELECT * FROM {$tb_prefix}biller WHERE enabled  ORDER BY name";
	$query = mysqlQuery($sql) or die(mysql_error());

	#biller selector

	if (mysql_num_rows($query) == 0) {
		//no records
		$display_block = "<p><em>{$LANG['no_billers']}</em></p>";

	} else {

		$display_block_biller = '<select name="value">
			<option value=0> </option>
			';

		while ($result = mysql_fetch_array($query)) {

			$selected = $result['id'] == $defaults['biller']?"selected style='font-weight: bold'":"";
			
			$display_block_biller .= <<<EOD
			<option $selected value="$result[id]">$result[name]</option>
EOD;
		}
		$display_block_biller .= "</select>";
	}

	$display_block = <<<EOD
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['biller_name']}</th><td>$display_block_biller</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
EOD;

}


else if ($_GET[submit] == "customer") {


	#customer
	$sql = "SELECT * FROM {$tb_prefix}customers WHERE enabled != 0 ORDER BY name";
	$query = mysqlQuery($sql);

	#customer selector

	if (mysql_num_rows($result_customer) == 0) {
		//no records
		$display_block_customer = "<p><em>{$LANG['no_customers']}</em></p>";

	} else {
		$default = "customer";
		//has records, so display them
		$display_block_customer = <<<EOD
	        <select name="value">
                <option value='0'> </option>
EOD;


		while ($recs_customer = mysql_fetch_array($query)) {

			$selected = $recs_customer['id'] == $defaults['customer']?"selected style='font-weight: bold'":"";
			
			$display_block_customer .= <<<EOD
			<option $selected value="$recs_customer[id]">$recs_customer[name]</option>
EOD;
		}
		$display_block_biller .= "</select>";
		
	}

	$display_block = <<<EOD
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['customer_name']}</th><td>$display_block_customer</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
EOD;

}



else if ($_GET['submit'] == "tax") {

	$default = "tax";
	#tax query
	$print_tax = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $defaults[tax]";
	$result_print_tax = mysqlQuery($print_tax, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax)) {
		$tax_idField = $Array_tax['tax_id'];
		$tax_descriptionField = $Array_tax['tax_description'];
	}


	#tax query
	$sql_tax = "SELECT * FROM {$tb_prefix}tax where tax_enabled != 0 ORDER BY tax_description";
	$result_tax = mysqlQuery($sql_tax, $conn) or die(mysql_error());


	#tax selector

	if (mysql_num_rows($result_tax) == 0) {
		//no records
		$display_block_tax = "<p><em>{$LANG['no_tax_rates']}</em></p>";

	} else {
		//has records, so display them
		$display_block_tax = <<<EOD
	        <select name="value">

                <option selected value="$defaults[tax]" style="font-weight: bold">$tax_descriptionField</option>
                <option value='0'> </option>
EOD;

		while ($recs_tax = mysql_fetch_array($result_tax)) {
			$id_tax = $recs_tax['tax_id'];
			$display_name_tax = $recs_tax['tax_description'];

			$display_block_tax .= <<<EOD
			<option  value="$id_tax">
                        $display_name_tax</option>
EOD;
		}
	}

	$display_block = <<<EOD
	<tr>
		<td><br></td>
	</tr>
	<tr>
	<td class="details_screen">{$LANG['tax']}</td><td>$display_block_tax</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
EOD;
}

else if ($_GET[submit] == "preference_id") {
	
	$preference = getPreference($defaults['preference']);

	#invoice preference query
	$sql_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_enabled != 0 ORDER BY pref_description";
	$result_preferences = mysqlQuery($sql_preferences, $conn) or die(mysql_error());


	#invoice_preference selector

	if (mysql_num_rows($result_preferences) == 0) {
		//no records
		$display_block_preferences = "<p><em>{$LANG['no_preferences']}</em></p>";

	} else {
		$default = "invoice";
		//has records, so display them
		$display_block_preferences = <<<EOD
	        <select name="value">

                <option selected value="$defaults[preference]" style="font-weight: bold">{$preference['pref_description']}</option>
                <option value='0'> </option>
EOD;

		while ($recs_preferences = mysql_fetch_array($result_preferences)) {
			$id_preferences = $recs_preferences['pref_id'];
			$display_name_preferences = $recs_preferences['pref_description'];

			$display_block_preferences .= <<<EOD
			<option value="$id_preferences">
	                        $display_name_preferences</option>
EOD;
		}
	}

	$display_block = <<<EOD
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['inv_pref']}</td><td>$display_block_preferences</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
EOD;

}

else if ($_GET[submit] == "def_payment_type") {

	#payment type query
	$print_payment_type = "SELECT * FROM {$tb_prefix}payment_types WHERE pt_id = $defaults[payment_type]";
	$result_print_payment_type = mysqlQuery($print_payment_type, $conn) or die(mysql_error());


	while ($Array_payment_type = mysql_fetch_array($result_print_payment_type)) {
		$pt_idField = $Array_payment_type['pt_id'];
		$pt_descriptionField = $Array_payment_type['pt_description'];
	}


	#payment type query
	$sql_payment_type = "SELECT * FROM {$tb_prefix}payment_types where pt_enabled != 0 ORDER BY pt_description";
	$result_payment_type = mysqlQuery($sql_payment_type, $conn) or die(mysql_error());


	#payment type selector

	if (mysql_num_rows($result_payment_type) == 0) {
		//no records
		$display_block_payment_type = "<p><em>{$LANG['payment_type']}</em></p>";

	} else {
		$default = "payment_type";
		//has records, so display them
		$display_block_payment_type = <<<EOD
                <select name="value">

                <option selected value="$defaults[payment_type]" style="font-weight: bold">$pt_descriptionField</option>
EOD;

		while ($recs_payment_type = mysql_fetch_array($result_payment_type)) {
			$id_payment_type = $recs_payment_type['pt_id'];
			$display_name_payment_type = $recs_payment_type['pt_description'];

			$display_block_payment_type .= <<<EOD
			<option value="$id_payment_type">
                        $display_name_payment_type</option>
EOD;
		}
	}

	$display_block = <<<EOD
        <tr>
                <td><br></td>
        </tr>
        <tr>
        <td class="details_screen">{$LANG['payment_type']}</td><td>$display_block_payment_type</td>
        </tr>
        <tr>
                <td><br></td>
        </tr>
EOD;

}


else {
	$display_block = "{$LANG['no_defaults']}";
}


echo <<<EOD

<form name="frmpost" action="index.php?module=system_defaults&view=save" method="post" onsubmit="return frmpost_Validator(this)">

		<b>{$LANG['system_defaults']}</b>
 <hr></hr>

<table align=center>

$display_block

</tr>
</tr>
</table>
<!-- </div> -->
	<input type="hidden" name="name" value="$default">
	<input type=submit name="submit" value="{$LANG['save_defaults']}">
	<input type=hidden name="op" value="update_system_defaults">

</form>
EOD;
?>
