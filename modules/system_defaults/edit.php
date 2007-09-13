<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$pageActive = "options";


#system defaults query

$defaults = getSystemDefaults();

if ($_GET["submit"] == "line_items") {

	jsBegin();
	jsFormValidationBegin("frmpost");
	jsValidateifNum("def_num_line_items","Default number of line items");
	jsFormValidationEnd();
	jsEnd();

	$default = "line_items";

	$value = <<<EOD
<input type=text size=25 name="value" value=$defaults[line_items]>
EOD;
	$description = "{$LANG['default_number_items']}";

}
else if ($_GET["submit"] == "def_inv_template") {

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


	$description = <<<EOD
	{$LANG['default_inv_template']} <a href='docs.php?t=help&p=default_invoice_template_text' rel='gb_page_center[450, 450]'><img src="images/common/help-small.png"></img></a>
EOD;
	$value = $display_block_templates_list;

}

else if ($_GET["submit"] == "biller") {

	$default = "biller";

	#biller query
	$billers = getActiveBillers();

	if ($billers == null) {
		$display_block = "<p><em>{$LANG['no_billers']}</em></p>";
	}
	else {

		$display_block_biller = '<select name="value">
			<option value=0> </option>';

		foreach($billers as $biller) {

			$selected = $biller['id'] == $defaults['biller']?"selected style='font-weight: bold'":"";
			
			$display_block_biller .= <<<EOD
			<option $selected value="$result[id]">$biller[name]</option>
EOD;
		}
		$display_block_biller .= "</select>";
	}

	$description = "{$LANG['biller_name']}";
	$value = $display_block_biller;
}


else if ($_GET["submit"] == "customer") {

	$customers = getActiveCustomers();

	if ($customers == null) {
		//no records
		$display_block_customer = "<p><em>{$LANG['no_customers']}</em></p>";

	} else {
		$default = "customer";
		//has records, so display them
		$display_block_customer = <<<EOD
	        <select name="value">
                <option value='0'> </option>
EOD;


		foreach($customers as $customer) {

			$selected = $customer['id'] == $defaults['customer']?"selected style='font-weight: bold'":"";
			
			$display_block_customer .= <<<EOD
			<option $selected value="$recs_customer[id]">$customer[name]</option>
EOD;
		}
		$display_block_customer .= "</select>";
		
	}

	$value = $display_block_customer;
	$description = "{$LANG['customer_name']}";
}



else if ($_GET['submit'] == "tax") {
	$default = "tax";

	$taxes = getActiveTaxes();

	if ($taxes == null) {
		//no records
		$display_block_tax = "<p><em>{$LANG['no_tax_rates']}</em></p>";

	} else {
		//has records, so display them
		$display_block_tax = <<<EOD
	        <select name="value">

                <option selected value="$defaults[tax]" style="font-weight: bold">{$tax['tax_description']}</option>
                <option value='0'> </option>
EOD;

		foreach($taxes as $tax) {

			$display_block_tax .= <<<EOD
			<option  value="$tax[tax_id]">
                        {$tax['tax_description']}</option>
EOD;
		}
	}

	$description = "{$LANG['tax']}";
	$value = $display_block_tax;
}
else if ($_GET["submit"] == "preference_id") {
	
	$pref = getPreference($defaults['preference']);
	$preferences = getActivePreferences();

	if ($preferences == null) {
		//no records
		$display_block_preferences = "<p><em>{$LANG['no_preferences']}</em></p>";

	} else {
		$default = "invoice";
		//has records, so display them
		$display_block_preferences = <<<EOD
	        <select name="value">

                <option selected value="$defaults[preference]" style="font-weight: bold">{$pref['pref_description']}</option>
                <option value='0'> </option>
EOD;

		foreach($preferences as $preference) {

			$display_block_preferences .= <<<EOD
			<option value="{$preference['pref_id']}">
	                        {$preference['pref_description']}</option>
EOD;
		}
	}

	$value = $display_block_preferences;
	$description = "{$LANG['inv_pref']}";

}

else if ($_GET["submit"] == "def_payment_type") {

	$defpay = getDefaultPaymentType();
	$payments = getActivePaymentTypes();
	

	if ($payments == null) {
		//no records
		$display_block_payment_type = "<p><em>{$LANG['payment_type']}</em></p>";

	} else {
		$default = "payment_type";
		//has records, so display them
		$display_block_payment_type = <<<EOD
                <select name="value">

                <option selected value="$defaults[payment_type]" style="font-weight: bold">{$defpay['pt_description']}</option>
EOD;

		foreach($payments as $payment) {

			$display_block_payment_type .= <<<EOD
			<option value="{$payment['pt_id']}">
                        {$payment['pt_description']}</option>
EOD;
		}
	}

	$description = "{$LANG['payment_type']}";
	$value = $display_block_payment_type;

}

else if ($_GET["submit"] == "delete") {

	$deleteArray = array(0 => $LANG[disabled], 1=>$LANG[enabled]);

	$default = "delete";
	//has records, so display them

	$dropDown = <<<EOD
         <select name="value">
EOD;

	foreach ($deleteArray as $key => $value)
	{
		$key == $defaults[delete]?$selected ="selected":$selected="";
		$dropDown .= '<OPTION '.$selected.' value='.$key.'> '.$value.'';
	} 

	$dropDown .= "</select>";
	$value = $dropDown;
	$description = "LANG_TODO:Delete stuff {$LANG['delete']}";

}

else if ($_GET['submit'] == "logging") {

	$array = array(0 => $LANG[disabled], 1=>$LANG[enabled]);

	$default = "logging";
	//has records, so display them

	$dropDown = <<<EOD
         <select name="value">
EOD;

	foreach ($array as $key => $value)
	{
		$key == $defaults[logging]?$selected ="selected":$selected="";
		$dropDown .= '<OPTION '.$selected.' value='.$key.'> '.$value.'';
	} 

	$dropDown .= "</select>";

	$description = "LANG_TODO: Logging";
	$value = $dropDown;
}
else if($_GET['submit'] == "language") {
	$languages = getLanguageList();
	
	//print_r($languages);
	$value = "<select>";
	foreach($languages as $language) {
		$value .= "<option>$language->name ($language->shortname)</option>";
	}
	$value .= "</select>";
	
	//print_r($folders);
}
else {
	$description = "{$LANG['no_defaults']}";
}



$pageActive = "options";

/*$smarty->assign('pageActive', $pageActive);
$smarty->assign('files', $files);
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('save', $save);
$smarty->assign('defaults', $defaults);
$smarty->assign('lang', $lang);
$smarty->assign('billers',$billers);*/
$smarty->assign('value',$value);
$smarty->assign('description',$description);
?>
