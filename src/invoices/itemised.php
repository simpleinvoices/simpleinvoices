<?php
include('./include/include_main.php');
include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsTextValidation("sel_id","Biller Name",1,100);
jsTextValidation("select_customer","Customer Name",1,100);
jsValidateifNumZero("i_quantity0","Quantity");
jsValidateifNum("i_quantity0","Quantity");
jsValidateRequired("select_products0","Product");
jsTextValidation("select_tax","Tax Rate",1,100);
jsPreferenceValidation("select_preferences","Invoice Preference",1,100);
jsFormValidationEnd();
jsEnd();


$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

#biller query
$sql = "SELECT * FROM si_biller where b_enabled != 0 ORDER BY b_name";
$result = mysql_query($sql, $conn) or die(mysql_error());

#customer
$sql_customer = "SELECT * FROM si_customers where c_enabled != 0 ORDER BY c_name";
$result_customer = mysql_query($sql_customer, $conn) or die(mysql_error());

#productr query
$sql_products = "SELECT * FROM si_products where prod_enabled != 0 ORDER BY prod_description";
$result_products = mysql_query($sql_products, $conn) or die(mysql_error());


#tax query
$sql_tax = "SELECT * FROM si_tax ORDER BY tax_description" ;
$result_tax = mysql_query($sql_tax, $conn) or die(mysql_error());

#invoice preference query
$sql_preferences = "SELECT * FROM si_preferences where pref_enabled != 0 ORDER BY pref_description";
$result_preferences = mysql_query($sql_preferences, $conn) or die(mysql_error());


#defaults query and DEFAULT NUMBER OF LINE ITEMS
$sql_defaults = "SELECT * FROM si_defaults";
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
$sql_biller_default = "SELECT b_name FROM si_biller where b_id = $def_billerField and b_enabled != 0";
$result_biller_default = mysql_query($sql_biller_default , $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_biller_default) ) {
                $sql_biller_defaultField = $Array['b_name'];
}

#default customer name query
$print_customer = "SELECT * FROM si_customers WHERE c_id = $def_customerField and c_enabled != 0";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

while ($Array_customer = mysql_fetch_array($result_print_customer)) {
       $c_nameField = $Array_customer['c_name'];
}

#default tax description query
$print_tax = "SELECT * FROM si_tax WHERE tax_id = $def_taxField and tax_enabled != 0";
$result_print_tax = mysql_query($print_tax, $conn) or die(mysql_error());

while ($Array_tax = mysql_fetch_array($result_print_tax)) {
       $tax_descriptionField = $Array_tax['tax_description'];
}

#default invoice preference description query
$print_inv_preference = "SELECT * FROM si_preferences WHERE pref_id = $def_inv_preferenceField";
$result_inv_preference = mysql_query($print_inv_preference, $conn) or die(mysql_error());

while ($Array_inv_preference = mysql_fetch_array($result_inv_preference)) {
       $pref_descriptionField = $Array_inv_preference['pref_description'];
}

#Get the names of the defaults from their id -end
#default biller name query



#biller selector

if (mysql_num_rows($result) == 0) {
        //no records
        $display_block = "<p><em>$mb_no_invoices</em></p>";

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
        $display_block_customer = "<p><em$mc_no_invoices</em></p>";

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

function line_items($line) {
        #productr query
        include('./config/config.php');
        $conn = mysql_connect("$db_host","$db_user","$db_password");
        mysql_select_db("$db_name",$conn);

        $sql_products = "SELECT * FROM si_products where prod_enabled != 0 ORDER BY prod_description";
        $result_products = mysql_query($sql_products, $conn) or die(mysql_error());

if (mysql_num_rows($result_products) == 0) {
        //no records
        $display_block_products = "<p><em>$mp_no_invoices</em></p>";

} else {
        //has records, so display them
        $display_block_products = "
        <select name=\"select_products$line\">
        <option value=\"\"></option>";

        while ($recs_products = mysql_fetch_array($result_products)) {
                $id_products = $recs_products['prod_id'];
                $display_name_products = $recs_products['prod_description'];

                $display_block_products .= "<option value=\"$id_products\">
                        $display_name_products</option>";
        }
        }
                echo "<tr>
                <td><input type=text name='i_quantity$line' size=5></td><td input type=text name='i_description$line' size=50>$display_block_products</td></tr>";
}


#tax selector

if (mysql_num_rows($result_tax) == 0) {
        //no records
        $display_block_tax = "<p><em>$mtr_no_invoices</em></p>";

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
        $display_block_preferences = "<p><em>$mip_no_invoices</em></p>";

} else {
        //has records, so display them
        $display_block_preferences = "
        <select name=\"select_preferences\">
        
        <option selected value=\"$def_inv_preferenceField\" style=\"font-weight: bold\">$pref_descriptionField</option>
	
	<option value=\"\"></option>";

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

<link rel="stylesheet" type="text/css" href="include/jquery.datePicker.css" title="default" media="screen" />

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery.dom_creator.js"></script>
<script type="text/javascript" src="include/jquery.datePicker.js"></script>
<script type="text/javascript" src="include/jquery.datePicker.conf.js"></script>


</head>

	<title><?php echo $title; echo " :: "; echo $LANG_inv; echo $LANG_inv_itemised; ?></title>
<?php include('./config/config.php'); ?>

<BODY>

<FORM name="frmpost" ACTION="index.php?module=invoices&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<b><?php echo $LANG_inv; echo $LANG_inv_itemised; ?></b>
<hr></hr>
      <div id="browser">


<table align=center>


<tr>
	<td class="details_screen">
		<?php echo $mb_table_biller_name; ?>
	</td>
	<td input type=text name="biller_block" size=25>
		<?php echo $display_block; ?>
	</td>
</tr>
</tr>
<tr>
	<td class="details_screen">
		<?php echo $mc_table_customer_name; ?>
	</td>
	<td input type=text name="customer_block" size=25 >
		<?php echo $display_block_customer; ?>
	</td>
</tr>
<tr>
        <td class="details_screen"><?php echo $LANG_date_formatted; ?></td>
        <td>
                        <input type="text" class="date-picker" name="select_date" id="date1" value='<?php echo $today = date("Y-m-d"); ?>'></input>
        </td>
</tr>

<tr>
<td class="details_screen"><?php echo $LANG_quantity;?></td><td class="details_screen"><?php echo $LANG_description;?></td>
</tr>
<?php
/* check the def number of line items and do the print and entry field for that number of items */
   /*Preparation work fro being able to dynamically add line items during an itemised invoice  */
	/*get the number of line items from the GET or if not set from the default in the database */
	if (!empty( $_GET['get_num_line_items'] )) {
		$dynamic_line_items = $_GET['get_num_line_items'];
		} 
	else {
		$dynamic_line_items = $def_number_line_itemsField ;
	}	

	$num = 0;

        while ($num < $dynamic_line_items ) :
                echo line_items($num);
                echo "</td></tr>";
                        $num++;

                        endwhile;
?>
<tr>
        <td colspan=5 class="details_screen"><?php echo $LANG_notes_opt;?></td>
</tr>

<tr>
        <td colspan=5><textarea input type=text name="invoice_itemised_note" rows=5 cols=70 WRAP=nowrap></textarea></td>
</tr>


<tr><td class="details_screen"><?php echo $LANG_tax;?></td><td input type=text name="inv_it_tax" size=15> <?php echo $display_block_tax; ?></td>
</tr>

<tr>
<td class="details_screen"><?php echo $LANG_inv_pref;?></td><td input type=text name="inv_preferences"><?php echo $display_block_preferences; ?></td>
</tr>
<!--Add more line items while in an itemeised invoice - Get style - has problems- wipes the current values of the existing rows - not good
<tr>
<td>
<a href="?get_num_line_items=10">Add 5 more line items<a>
</tr>
-->
</table>
<!-- </div> -->
<hr></hr>
		<input type=hidden name="max_items" value="<?php echo $num; ?>">
		<input type=submit name="submit" value="<?php echo $LANG_save;echo " "; echo $LANG_inv;?>">
		<input type=hidden name="invoice_style" value="insert_invoice_itemised">
		* <?php echo $LANG_mandatory_fields;?>

</FORM>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
