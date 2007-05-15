<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

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




#biller query
$sql = "SELECT * FROM {$tb_prefix}biller where enabled != 0 ORDER BY name";
$result = mysqlQuery($sql, $conn) or die(mysql_error());

#customer
$sql_customer = "SELECT * FROM {$tb_prefix}customers where enabled != 0 ORDER BY name";
$result_customer = mysqlQuery($sql_customer, $conn) or die(mysql_error());

#productr query
$sql_products = "SELECT * FROM {$tb_prefix}products where enabled != 0 ORDER BY description";
$result_products = mysqlQuery($sql_products, $conn) or die(mysql_error());


#tax query
$sql_tax = "SELECT * FROM {$tb_prefix}tax ORDER BY tax_description" ;
$result_tax = mysqlQuery($sql_tax, $conn) or die(mysql_error());

#invoice preference query
$sql_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_enabled != 0 ORDER BY pref_description";
$result_preferences = mysqlQuery($sql_preferences, $conn) or die(mysql_error());


#defaults query and DEFAULT NUMBER OF LINE ITEMS
/*$sql_defaults = "SELECT * FROM {$tb_prefix}defaults";
$result_defaults = mysqlQuery($sql_defaults, $conn) or die(mysql_error());

while ($Array_defaults = mysql_fetch_array($result_defaults) ) {
                $def_idField = $Array_defaults['def_id'];
                $def_billerField = $Array_defaults['def_biller'];
                $def_customerField = $Array_defaults['def_customer'];
                $def_taxField = $Array_defaults['def_tax'];
                $def_inv_preferenceField = $Array_defaults['def_inv_preference'];
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
};
*/
$defaults = getSystemDefaults();

#Get the names of the defaults from their id -start
#default biller name query
$sql_biller_default = "SELECT name FROM {$tb_prefix}biller where id = $defaults[biller] and enabled != 0";
$result_biller_default = mysqlQuery($sql_biller_default , $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_biller_default) ) {
                $sql_biller_defaultField = $Array['name'];
}

#default customer name query
$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE id = $defaults[customer] and enabled != 0";
$result_print_customer = mysqlQuery($print_customer, $conn) or die(mysql_error());

$defaultCustomer = mysql_fetch_array($result_print_customer);
	
while ($Array_customer = mysql_fetch_array($result_print_customer)) {
       $c_nameField = $Array_customer['name'];
}

#default tax description query
$print_tax = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $defaults[tax] and tax_enabled != 0";
$result_print_tax = mysqlQuery($print_tax, $conn) or die(mysql_error());

while ($Array_tax = mysql_fetch_array($result_print_tax)) {
       $tax_descriptionField = $Array_tax['tax_description'];
}

#default invoice preference description query
$print_inv_preference = "SELECT * FROM {$tb_prefix}preferences WHERE pref_id = $defaults[invoice]";
$result_inv_preference = mysqlQuery($print_inv_preference, $conn) or die(mysql_error());

while ($Array_inv_preference = mysql_fetch_array($result_inv_preference)) {
       $pref_descriptionField = $Array_inv_preference['pref_description'];
}

#Get the names of the defaults from their id -end
#default biller name query



#biller selector

if (mysql_num_rows($result) == 0) {
        //no records
        $display_block = "<p><em>{$LANG['no_billers']}</em></p>";

} else {
        //has records, so display them
        $display_block = <<<EOD
        <select name="sel_id">"
        <option selected value="$defaults[biller]" style="font-weight: bold">$sql_biller_defaultField</option>
        <option value=""></option>
EOD;

        while ($recs = mysql_fetch_array($result)) {
                $id = $recs['id'];
                $display_name = $recs['name'];

                $display_block .= <<<EOD
                "<option value="$id">
                        $display_name</option>
EOD;
        }
}

#customer selector

if (mysql_num_rows($result_customer) == 0) {
        //no records
        $display_block_customer = "<p><em>{$LANG['no_customers']}</em></p>";

} else {
        //has records, so display them
        $display_block_customer = <<<EOD
        <select name="select_customer">
        <option selected value="$defaults[customer]" style="font-weight: bold">$defaultCustomer[name]</option>
        <option value=""></option>
EOD;

        while ($recs_customer = mysql_fetch_array($result_customer)) {
                $id_customer = $recs_customer['id'];
                $display_name_customer = $recs_customer['name'];

                $display_block_customer .= <<<EOD
                <option value="$id_customer">
                        $display_name_customer</option>
EOD;
        }
}

function line_items($line) {
        #productr query
        include('./config/config.php');
        $conn = mysql_connect("$db_host","$db_user","$db_password");
        mysql_select_db("$db_name",$conn);

        $sql_products = "SELECT * FROM {$tb_prefix}products where enabled != 0 ORDER BY description";
        $result_products = mysqlQuery($sql_products, $conn) or die(mysql_error());

if (mysql_num_rows($result_products) == 0) {
        //no records
        $display_block_products = "<p><em>{$LANG['no_products']}</em></p>";

} else {
        //has records, so display them
        $display_block_products = <<<EOD
        <select name="select_products$line">
        <option value=""></option>
EOD;

        while ($recs_products = mysql_fetch_array($result_products)) {
                $id_products = $recs_products['id'];
                $display_name_products = $recs_products['description'];

                $display_block_products .= <<<EOD
                <option value="$id_products">
                        $display_name_products</option>
EOD;
        }
        }
                echo "<tr>
                <td><input type=text name='i_quantity$line' size=5></td><td input type=text name='i_description$line' size=50>$display_block_products</td></tr>";
}


#tax selector

if (mysql_num_rows($result_tax) == 0) {
        //no records
        $display_block_tax = "<p><em>{$LANG['no_tax_rates']}</em></p>";

} else {
        //has records, so display them
        $display_block_tax = <<<EOD
        <select name="select_tax">
        
	<option selected value="$defaults[tax]" style="font-weight: bold">$tax_descriptionField</option>
        
	<option value=""></option>
EOD;

        while ($recs_tax = mysql_fetch_array($result_tax)) {
                $id_tax = $recs_tax['tax_id'];
                $display_name_tax = $recs_tax['tax_description'];

                $display_block_tax .= <<<EOD
                <option value="$id_tax">
                        $display_name_tax</option>
EOD;
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
        
        <option selected value="$defaults[invoice]" style="font-weight: bold">$pref_descriptionField</option>
	
	<option value=""></option>
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


#get custom field labels

$show_custom_field_1 = show_custom_field(invoice_cf1,'',write,'',details_screen,'','','');
$show_custom_field_2 = show_custom_field(invoice_cf2,'',write,'',details_screen,'','','');
$show_custom_field_3 = show_custom_field(invoice_cf3,'',write,'',details_screen,'','','');
$show_custom_field_4 = show_custom_field(invoice_cf4,'',write,'',details_screen,'','','');

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



</head>

	<title><?php echo $title; echo " :: "; echo $LANG['inv']; echo $LANG['inv_itemised']; ?></title>
<?php include('./config/config.php'); ?>

<BODY>

<FORM name="frmpost" ACTION="index.php?module=invoices&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<b><?php echo $LANG['inv']; echo $LANG['inv_itemised']; ?></b>
<hr></hr>


<table align=center>


<tr>
	<td class="details_screen">
		<?php echo $LANG['biller_name']; ?>
	</td>
	<td input type=text name="biller_block" size=25>
		<?php echo $display_block; ?>
	</td>
</tr>
<tr>
	<td class="details_screen">
		<?php echo $LANG['customer_name']; ?>
	</td>
	<td input type=text name="customer_block" size=25 >
		<?php echo $display_block_customer; ?>
	</td>
</tr>
<tr>
        <td class="details_screen"><?php echo $LANG['date_formatted']; ?></td>
        <td>
                        <input type="text" class="date-picker" name="select_date" id="date1" value='<?php echo $today = date("Y-m-d"); ?>'></input>
        </td>
</tr>

<tr>
<td class="details_screen"><?php echo $LANG['quantity'];?></td><td class="details_screen"><?php echo $LANG['description'];?></td>
</tr>
<?php
/* check the def number of line items and do the print and entry field for that number of items */
   /*Preparation work fro being able to dynamically add line items during an itemised invoice  */
	/*get the number of line items from the GET or if not set from the default in the database */
	if (!empty( $_GET['get_num_line_items'] )) {
		$dynamic_line_items = $_GET['get_num_line_items'];
		} 
	else {
		$dynamic_line_items = $defaults['items'] ;
	}	

	$num = 0;

        while ($num < $dynamic_line_items ) :
                echo line_items($num);
                echo "</td></tr>";
                        $num++;

                        endwhile;
?>

<?php 
	echo $show_custom_field_1;
	echo $show_custom_field_2;
	echo $show_custom_field_3;
	echo $show_custom_field_4;
?>

<tr>
        <td colspan=2 class="details_screen"><?php echo $LANG['notes'];?></td>
</tr>

<tr>
        <td colspan=2><textarea input type=text name="invoice_itemised_note" rows=5 cols=70 WRAP=nowrap></textarea></td>
</tr>

<tr><td class="details_screen"><?php echo $LANG['tax'];?></td><td input type=text name="tax" size=15> <?php echo $display_block_tax; ?></td>
</tr>

<tr>
<td class="details_screen"><?php echo $LANG['inv_pref'];?></td><td input type=text name="preference_id"><?php echo $display_block_preferences; ?></td>
</tr>	
<tr>
	<td align=left>
		<a href="docs.php?t=help&p=invoice_custom_fields" rel="gb_page_center[450, 450]"><?php echo $LANG['want_more_fields']; ?><img src="./images/common/help-small.png"></img></a>

	</td>
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
		<input type=submit name="submit" value="<?php echo $LANG['save_invoice']; ?>">
		<input type=hidden name="invoice_style" value="insert_invoice_itemised">

</FORM>
