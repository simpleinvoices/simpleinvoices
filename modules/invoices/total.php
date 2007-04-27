<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

jsBegin();
jsFormValidationBegin("frmpost");
jsTextValidation("sel_id","Biller Name",1,100);
jsTextValidation("select_customer","Customer Name",1,100);
jsValidateifNum("inv_it_gross_total","Gross Total");
jsTextValidation("select_tax","Tax Rate",1,100);
jsPreferenceValidation("select_preferences","Invoice Preference",1,100);
jsFormValidationEnd();
jsEnd();





#biller query
$sql = "SELECT * FROM {$tb_prefix}biller where b_enabled != 0 ORDER BY name";
$result = mysql_query($sql, $conn) or die(mysql_error());

#customer
$sql_customer = "SELECT * FROM {$tb_prefix}customers where c_enabled != 0 ORDER BY c_name";
$result_customer = mysql_query($sql_customer, $conn) or die(mysql_error());


#tax query
$sql_tax = "SELECT * FROM {$tb_prefix}tax where tax_enabled != 0 ORDER BY tax_description";
$result_tax = mysql_query($sql_tax, $conn) or die(mysql_error());

#invoice preference query
$sql_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_enabled != 0 ORDER BY pref_description";
$result_preferences = mysql_query($sql_preferences, $conn) or die(mysql_error());

#DEFAULTS
#defaults query and DEFAULT NUMBER OF LINE ITEMS
$sql_defaults = "SELECT * FROM {$tb_prefix}defaults";
$result_defaults = mysql_query($sql_defaults, $conn) or die(mysql_error());

while ($Array_defaults = mysql_fetch_array($result_defaults) ) {
                $def_idField = $Array_defaults['def_id'];
                $def_billerField = $Array_defaults['def_biller'];
                $def_customerField = $Array_defaults['def_customer'];
                $def_taxField = $Array_defaults['def_tax'];
                $def_inv_preferenceField = $Array_defaults['def_inv_preference'];
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
};
#Get the names of the defaults from their id -start
#default biller name query
$sql_biller_default = "SELECT b_name FROM {$tb_prefix}biller where b_id = $def_billerField";
$result_biller_default = mysql_query($sql_biller_default , $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_biller_default) ) {
                $sql_biller_defaultField = $Array['b_name'];
}

#default customer name query
$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE c_id = $def_customerField";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

while ($Array_customer = mysql_fetch_array($result_print_customer)) {
       $c_nameField = $Array_customer['c_name'];
}

#default tax description query
$print_tax = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $def_taxField";
$result_print_tax = mysql_query($print_tax, $conn) or die(mysql_error());

while ($Array_tax = mysql_fetch_array($result_print_tax)) {
       $tax_descriptionField = $Array_tax['tax_description'];
}

#default invoice preference description query
$print_inv_preference = "SELECT * FROM {$tb_prefix}preferences WHERE pref_id = $def_inv_preferenceField";
$result_inv_preference = mysql_query($print_inv_preference, $conn) or die(mysql_error());

while ($Array_inv_preference = mysql_fetch_array($result_inv_preference)) {
       $pref_descriptionField = $Array_inv_preference['pref_description'];
}

#Get the names of the defaults from their id -end
#default biller name query

#DEFAULTS - END

#get custom field labels

$show_custom_field_1 = show_custom_field(invoice_cf1,'',write,'',details_screen,'','','');
$show_custom_field_2 = show_custom_field(invoice_cf2,'',write,'',details_screen,'','','');
$show_custom_field_3 = show_custom_field(invoice_cf3,'',write,'',details_screen,'','','');
$show_custom_field_4 = show_custom_field(invoice_cf4,'',write,'',details_screen,'','','');



#biller selector

if (mysql_num_rows($result) == 0) {
        //no records
        $display_block = "<p><em>{$LANG['no_billers']}</em></p>";

} else {
        //has records, so display them
        $display_block = "
        <select name=\"sel_id\">
        <option selected value=\"$def_billerField\" style=\"font-weight: bold\">$sql_biller_defaultField</option>
        <option value=\"\"></option>";

        while ($recs = mysql_fetch_array($result)) {
                $id = $recs['b_id'];
                $display_name = $recs['b_name'];

                $display_block .= "<option value=\"$id\">
                        $display_name</option>";
        }
}

#customer selector

if (mysql_num_rows($result_customer) == 0) {
        //no records
        $display_block_customer = "<p><em>{$LANG['no_customers']}</em></p>";

} else {
        //has records, so display them
        $display_block_customer = "
        <select name=\"select_customer\">
        <option selected value=\"$def_customerField\" style=\"font-weight: bold\">$c_nameField</option>
        <option value=\"\"></option>";

        while ($recs_customer = mysql_fetch_array($result_customer)) {
                $id_customer = $recs_customer['c_id'];
                $display_name_customer = $recs_customer['c_name'];

                $display_block_customer .= "<option value=\"$id_customer\">
                        $display_name_customer</option>";
        }
}


#tax selector

if (mysql_num_rows($result_tax) == 0) {
        //no records
        $display_block_tax = "<p><em>{$LANG['no_tax_rates']}</em></p>";

} else {
        //has records, so display them
        $display_block_tax = "
        <select name=\"select_tax\">
        <option selected value=\"$def_taxField\" style=\"font-weight: bold\">$tax_descriptionField</option>
        <option value=\"\"></option>";

        while ($recs_tax = mysql_fetch_array($result_tax)) {
                $id_tax = $recs_tax['tax_id'];
                $display_name_tax = $recs_tax['tax_description'];

                $display_block_tax .= "<option value=\"$id_tax\">
                        $display_name_tax</option>";
        }
}

#invoice_preference selector

if (mysql_num_rows($result_preferences) == 0) {
        //no records
        $display_block_preferences = "<p><em>{$LANG['no_preferences']}</em></p>";

} else {
        //has records, so display them
        $display_block_preferences = <<<EOD
        <select name="select_preferences">
        <option selected value="$def_inv_preferenceField" style="font-weight:bold;">$pref_descriptionField</option>
        <option value=""></option>
EOD;

        while ($recs_preferences = mysql_fetch_array($result_preferences)) {
                $id_preferences = $recs_preferences['pref_id'];
                $display_name_preferences = $recs_preferences['pref_description'];

                $display_block_preferences .= "<option value=\"$id_preferences\">
                        $display_name_preferences</option>";
        }
}




?>



<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>

<link
	rel="stylesheet" type="text/css"
	href="include/jquery/jquery.datePicker.css" title="default"
	media="screen" />

<script
	type="text/javascript" src="include/jquery/jquery.js"></script>
<script
	type="text/javascript" src="include/jquery/jquery.dom_creator.js"></script>
<script
	type="text/javascript" src="include/jquery/jquery.datePicker.js"></script>
<script
	type="text/javascript" src="include/jquery/jquery.datePicker.conf.js"></script>

<title><?php echo $title; echo " :: "; echo $LANG['inv']; echo $LANG['inv_total']; ?></title>

<?php include('./config/config.php'); ?>
</head>
<BODY>


<FORM name="frmpost" ACTION="index.php?module=invoices&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<td colspan=2 align=center><b><?php echo $LANG['inv']; echo $LANG['inv_total']; ?></b></th>
<hr></hr>


<table  align=center>
<tr>
<td class="details_screen"><?php echo $LANG['biller_name']; ?></th><td input type=text name="biller_block" size=25><?php echo $display_block; ?></td>

</tr>
</tr>

<tr>
	<td class="details_screen">
		<?php echo $LANG['customer_name']; ?></th><td input type=text name="customer_block" size=25><?php echo $display_block_customer; ?>
	</td>
</tr>
<tr>
        <td class="details_screen"><?php echo $LANG['date_formatted']; ?></td>
        <td>
			<input type="text" class="date-picker" name="select_date" id="date1" value='<?php echo $today = date("Y-m-d"); ?>'></input>
	</td>
</tr>


<tr>
	<td colspan=5 class="details_screen"><?php echo $LANG['description'];?></td>
</tr>
<tr>
	<td colspan=5 class="details_screen" ><textarea input type=text name="i_description" rows=10 cols=100 WRAP=nowrap></textarea></td>
</tr>
<?php 
	echo $show_custom_field_1;
	echo $show_custom_field_2;
	echo $show_custom_field_3;
	echo $show_custom_field_4;
?>
<tr>
	<td class="details_screen"><?php echo $LANG['gross_total'];?></td><td class="details_screen"><?php echo $LANG['tax'];?></td><td class="details_screen"><?php echo $LANG['inv_pref'];?></td>
</tr>
<tr>
	<td><input type=text name="inv_it_gross_total" size=15></td><td input type=text name="inv_it_tax" size=15><?php echo $display_block_tax; ?></td><td input type=text name="inv_preferences" size=25><?php echo $display_block_preferences; ?></td>

</tr>
<tr>
	<td align=left>
		<a href="./documentation/info_pages/invoice_custom_fields.html" rel="gb_page_center[450, 450]"><?php echo $LANG['want_more_fields']; ?><img src="./images/common/help-small.png"></img></a>
	</td>
</tr>
</table>
<hr></hr>
	<input type=submit name="submit" value="<?php echo $LANG['save_invoice'];?>"><input type=hidden name="invoice_style" value="insert_invoice_total">
</FORM>
