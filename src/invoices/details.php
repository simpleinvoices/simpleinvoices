<?php
#table
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

#get the invoice id
$master_invoice_id = $_GET['submit'];


#Info from DB print
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

#Get the invoice details
$print_master_invoice_id = 'SELECT * FROM {$tb_prefix}invoices WHERE inv_id = ' . $master_invoice_id;
$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

while ($Array_master_invoice = mysql_fetch_array($result_print_master_invoice_id)) {
                $inv_idField = $Array_master_invoice['inv_id'];
                $inv_biller_idField = $Array_master_invoice['inv_biller_id'];
                $inv_customer_idField = $Array_master_invoice['inv_customer_id'];
                $inv_typeField = $Array_master_invoice['inv_type'];
                $inv_preferenceField = $Array_master_invoice['inv_preference'];
		$inv_dateField = date( 'Y-m-d', strtotime( $Array_master_invoice['inv_date'] ) );
		/*
		$inv_dateField = $Array_master_invoice['inv_date'];
		*/
                $inv_noteField = $Array_master_invoice['inv_note'];
                $inv_custom_field1Field = $Array_master_invoice['invoice_custom_field1'];
                $inv_custom_field2Field = $Array_master_invoice['invoice_custom_field2'];
                $inv_custom_field3Field = $Array_master_invoice['invoice_custom_field3'];
                $inv_custom_field4Field = $Array_master_invoice['invoice_custom_field4'];


};

#get all the details from the invoice_items table for this invoice
#items invoice id select
$print_master_invoice_items = "SELECT * FROM {$tb_prefix}invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());

while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
	$inv_it_idField = $Array_master_invoice_items['inv_it_id'];
        $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
        $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
        $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
        $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
        $inv_it_tax_idField = $Array_master_invoice_items['inv_it_tax_id'];
        $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
        $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
        $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
        $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
        $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];
};


/*
		$inv_it_total_tax_amount = $inv_it_gross_totalField / $inv_it_taxField  ;
                $inv_dateField = $Array_master_invoice['inv_date'];
*/


#invoice_type query
$sql_invoice_type = 'SELECT inv_ty_description FROM {$tb_prefix}invoice_type WHERE inv_ty_id = ' . $inv_typeField;
$result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
	$inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];
};


#CUSTOMER drop down list - start
	#customer
	$sql_customer = "SELECT * FROM {$tb_prefix}customers where c_enabled != 0 ORDER BY c_name";
	$result_customer = mysql_query($sql_customer, $conn) or die(mysql_error());

	#selected customer name query
	$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE c_id = $inv_customer_idField";
	$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

	while ($Array_customer = mysql_fetch_array($result_print_customer)) {
	       $c_nameField = $Array_customer['c_name'];
	}

	#do the customer selector stuff

	if (mysql_num_rows($result_customer) == 0) {
	        //no records
	        $display_block_customer = "<p><em>$mc_no_invoices</em></p>";

	} else {
	        //has records, so display them
	        $display_block_customer = "
	        <select name=\"select_customer\">
	        <option selected value=\"$inv_customer_idField\" style=\"font-weight: bold\">$c_nameField</option>";
	
	        while ($recs_customer = mysql_fetch_array($result_customer)) {
	                $id_customer = $recs_customer['c_id'];
	                $display_name_customer = $recs_customer['c_name'];
	
	                $display_block_customer .= "<option value=\"$id_customer\">$display_name_customer</option>";
	        }
	}
#sweet - customer drop down down
#CUSTOMER drop down list - end


#BILLER drop down list -start
	#biller query
	$sql_biller = "SELECT * FROM {$tb_prefix}biller where b_enabled != 0 ORDER BY b_name";
	$result_biller = mysql_query($sql_biller, $conn) or die(mysql_error());

	#Get the names of the selected biller -start
	$sql_biller_default = "SELECT b_name FROM {$tb_prefix}biller where b_id = $inv_biller_idField ";
	$result_biller_default = mysql_query($sql_biller_default , $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result_biller_default) ) {
                $sql_biller_defaultField = $Array['b_name'];
	}

	# do the biller selector

	if (mysql_num_rows($result_biller) == 0) {
	        //no records
	        $display_block_biller = "<p><em>$mb_no_invoices</em></p>";

	} else {
	        //has records, so display them
	        $display_block_biller = "
	        <select name=\"sel_id\">
	        <option selected value=\"$inv_biller_idField\" style=\"font-weight: bold\">$sql_biller_defaultField</option>";

        	while ($recs = mysql_fetch_array($result_biller)) {
                	$id = $recs['b_id'];
	                $display_name_biller = $recs['b_name'];
	
	                $display_block_biller .= "<option value=\"$id\">$display_name_biller</option>";
	        }
	}
	
#BILLER drop down list - end

#TAX drop down list - start
	#tax query
	$sql_tax = "SELECT * FROM {$tb_prefix}tax where tax_enabled != 0 ORDER BY tax_description";
	$result_tax = mysql_query($sql_tax, $conn) or die(mysql_error());

	#default tax description query
	$print_tax = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $inv_it_tax_idField";
	$result_print_tax = mysql_query($print_tax, $conn) or die(mysql_error());

	while ($Array_tax = mysql_fetch_array($result_print_tax)) {
	       $tax_descriptionField = $Array_tax['tax_description'];
	}

	#tax selector

	if (mysql_num_rows($result_tax) == 0) {
	        //no records
	        $display_block_tax = "<p><em>$mtr_no_invoices</em></p>";

	} else {
	        //has records, so display them
	        $display_block_tax = "
	        <select name=\"select_tax\">
	        <option selected value=\"$inv_it_tax_idField\" style=\"font-weight: bold\">$tax_descriptionField</option>";

        	while ($recs_tax = mysql_fetch_array($result_tax)) {
	                $id_tax = $recs_tax['tax_id'];
        	        $display_name_tax = $recs_tax['tax_description'];
	
	                $display_block_tax .= "<option value=\"$id_tax\">$display_name_tax</option>";
	        }
	}
#TAX drop down list - end

#PREFERENCE drop down list - start
	#invoice preference query
	$sql_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_enabled != 0 ORDER BY pref_description";
	$result_preferences = mysql_query($sql_preferences, $conn) or die(mysql_error());

	#default invoice preference description query
	$print_inv_preference = "SELECT * FROM {$tb_prefix}preferences WHERE pref_id = $inv_preferenceField";
	$result_inv_preference = mysql_query($print_inv_preference, $conn) or die(mysql_error());

	while ($Array_inv_preference = mysql_fetch_array($result_inv_preference)) {
	       $pref_descriptionField = $Array_inv_preference['pref_description'];
	}

	#invoice_preference selector

	if (mysql_num_rows($result_preferences) == 0) {
        	//no records
	        $display_block_preferences = "<p><em>$mip_no_invoices</em></p>";

	} else {
	        //has records, so display them
        	$display_block_preferences = "
	        <select name=\"select_preferences\">
	        <option selected value=\"$inv_preferenceField\" style=\"font-weight: bold\">$pref_descriptionField</option>";
	
	        while ($recs_preferences = mysql_fetch_array($result_preferences)) {
	                $id_preferences = $recs_preferences['pref_id'];
	                $display_name_preferences = $recs_preferences['pref_description'];
	
	                $display_block_preferences .= "<option value=\"$id_preferences\">$display_name_preferences</option>";
	        }
	}
####Preference selector - end

#preferences query
$print_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_id = $inv_preferenceField ";
$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());

while ($Array_preferences = mysql_fetch_array($result_print_preferences)) {
                $pref_descriptionField = $Array_preferences['pref_description'];
                $pref_currency_signField = $Array_preferences['pref_currency_sign'];
                $pref_inv_headingField = $Array_preferences['pref_inv_heading'];
                $pref_inv_wordingField = $Array_preferences['pref_inv_wording'];

};

#system defaults query
$print_defaults = "SELECT * FROM {$tb_prefix}defaults WHERE def_id = 1";
$result_print_defaults = mysql_query($print_defaults, $conn) or die(mysql_error());


while ($Array_defaults = mysql_fetch_array($result_print_defaults) ) {
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
                $def_inv_templateField = $Array_defaults['def_inv_template'];
};

$line = 1;


#get custom field labels

$show_custom_field_1 = show_custom_field(invoice_cf1,$inv_custom_field1Field,write,'',details_screen,'','','');
$show_custom_field_2 = show_custom_field(invoice_cf2,$inv_custom_field2Field,write,'',details_screen,'','','');
$show_custom_field_3 = show_custom_field(invoice_cf3,$inv_custom_field3Field,write,'',details_screen,'','','');
$show_custom_field_4 = show_custom_field(invoice_cf4,$inv_custom_field4Field,write,'',details_screen,'','','');
$display_block_top =  "


	<table align=center>
	<tr>
		<td colspan=6 align=center></td>
	</tr>
        <tr>
		<td class='details_screen'>$pref_inv_wordingField $LANG_number_short</td><td><input type=hidden name=\"invoice_id\" value=$inv_idField size=15>$inv_idField</td>
	</tr>
	<!--	
	<tr>
		<td class='details_screen'>$pref_inv_wordingField $LANG_date</td><td colspan=2>$inv_dateField</td>
	</tr>	
	-->
	<tr>
	        <td class=\"details_screen\">$LANG_date_formatted</td>
        	<td><input type=\"text\" class=\"date-picker\" name=\"select_date\" id=\"date1\" value='$inv_dateField'></input></td>
	</tr>
	<tr>
		<td class='details_screen'>$LANG_biller</td><td>$display_block_biller</td>
	</tr>
	<tr>
		<td class='details_screen'>$LANG_customer</td><td>$display_block_customer</td>
	</tr>	

";

#PRINT DETAILS FOR THE TOTAL STYLE INVOICE

if (  $_GET['invoice_style'] === 'Total' ) {
        #invoice total layout - no quantity

	#get all the details for the total style
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM {$tb_prefix}invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
                $inv_it_idField = $Array_master_invoice_items['inv_it_id'];
                $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
                $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];

	};
	
	/* - might not need - can delete after testing
	#products query
	$print_products = "SELECT * FROM {$tb_prefix}products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $Array['prod_id'];
                $prod_descriptionField = $Array['prod_description'];
                $prod_unit_priceField = $Array['prod_unit_price'];

	};

	#invoice_total total query
	$print_invoice_total_total ="select sum(inv_it_total) as total from {$tb_prefix}invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_total = mysql_query($print_invoice_total_total, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_invoice_total_total)) {
                $invoice_total_totalField = $Array['total'];

	};
	*/

	#all the details have bee got now print them to screen

	$display_block_details =  "
		<input type=hidden name=\"invoice_style\" value=\"edit_invoice_total\">
	        <tr>
        	        <td colspan=6 class='details_screen'>$LANG_description</td>
	        </tr>
	        <tr>
			<td colspan=6 ><textarea input type=text name=\"i_description\" rows=10 cols=70 WRAP=nowrap>$inv_it_descriptionField</textarea></td>
        	</tr>

	 $show_custom_field_1
	 $show_custom_field_2
	 $show_custom_field_3
	 $show_custom_field_4
	        <tr>       	         
			<td class='details_screen'>$LANG_gross_total</td><td><input type=text name='inv_it_gross_total' value='$inv_it_gross_totalField' size=10> </td>
		</tr>
		<tr>
		<tr>
			 <td class='details_screen'>$LANG_tax</td><td>$display_block_tax</td>
	        </tr>
			 <td class='details_screen'>$LANG_inv_pref</td><td>$display_block_preferences/td>
	        </tr>
	";	
   

     }

#INVOICE ITEMEISED and CONSULTING SECTION

else if ( $_GET['invoice_style'] === 'Itemised' || $_GET['invoice_style'] === 'Consulting' ) {

	$display_block_details =  "";
	
	#show column heading for itemised style
        if ( $_GET['invoice_style'] === 'Itemised' ) {
		$display_block_details .=  "      
		<input type=hidden name=\"invoice_style\" value=\"edit_invoice_itemised\">
		<tr>
		<td colspan=6>
		<table>
		<tr>
        	        <td class='details_screen'>$LANG_quantity_short</td><td class='details_screen'>$LANG_description</td>
	        </tr>";
	}
	#show column heading for consulting style
        else if ( $_GET['invoice_style'] === 'Consulting' ) {
                $display_block_details .=  "
		<input type=hidden name=\"invoice_style\" value=\"edit_invoice_consulting\">
		<tr>
		<td colspan=6>
		<table>
                <tr>
                        <td class='details_screen'>$LANG_quantity_short</td><td class='details_screen'>$LANG_item</td>
                </tr>";
        }




	#INVOIVE_ITEMS SECTION
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM {$tb_prefix}invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
                $inv_it_idField = $Array_master_invoice_items['inv_it_id'];
                $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
                $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];
	/*
	};
	*/

	#products query - for the selected product
	$print_products = "SELECT * FROM {$tb_prefix}products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $Array['prod_id'];
                $prod_descriptionField = $Array['prod_description'];

	
	#product query - for all the other ones
        $sql_products = "SELECT * FROM {$tb_prefix}products where prod_enabled != 0 ORDER BY prod_description";
        $result_products = mysql_query($sql_products, $conn) or die(mysql_error());

	if (mysql_num_rows($result_products) == 0) {
	        //no records
	        $display_block_products = "<p><em>$mp_no_invoices</em></p>";

	} else {
	        //has records, so display them
	        $display_block_products = "
	        <select name=\"select_products$line\">
	                <option selected value=\"$prod_idField\" style=\"font-weight: bold\">$prod_descriptionField</option>";

        	while ($recs_products = mysql_fetch_array($result_products)) {
	                $id_products = $recs_products['prod_id'];
	                $display_name_products = $recs_products['prod_description'];
	
	                $display_block_products .= "<option value=\"$id_products\">
	                        $display_name_products</option>";
	        }
	        }
	}


	#MERGE ITEMISED AND CONSULTING HERE
	#PRINT the line items
	#show the itemised invoice
	if ( $_GET['invoice_style'] === 'Itemised' ) {

		
		$display_block_details .=  "
	        <tr>
			<td><input type=text name='i_quantity$line' value='$inv_it_quantityField' size=10><input type=hidden text name='inv_it_id$line' value='$inv_it_idField' size=10> </td>
			
	                <td input type=text name='i_description$line' size=50>$display_block_products</td>
	        </tr>
		";
	
	$line++;
	}	

	#show the consulting invoice 
	else if ( $_GET['invoice_style'] === 'Consulting' ) {
		

	        $display_block_details .=  "
        	<tr>
                        <td><input type=text name='i_quantity$line' value='$inv_it_quantityField' size=10><input type=hidden text name='inv_it_id$line' value='$inv_it_idField' size=10> </td>

                        <td input type=text name='i_description$line' size=50>$display_block_products</td>
        	</tr> 
		<tr>

			<td colspan=6 class='details_screen'>$LANG_description</td>
		<tr>
                        <td colspan=6 ><textarea input type=text name=\"consulting_item_note$line\" rows=5 cols=70 WRAP=nowrap>$inv_it_descriptionField</textarea></td>
                </tr>

		";

	$line++;

	}





	};

	#if itemised style show the invoice note field - START
	if ( $_GET['invoice_style'] === 'Itemised' OR 'Consulting')   {

		$display_block_details .=  "
			</table>
			$show_custom_field_1
			$show_custom_field_2
			$show_custom_field_3
			$show_custom_field_4
			<tr>
				<td colspan=6 class='details_screen'>$LANG_note:</td>
			</tr>
			<tr>
	                        <td colspan=6 ><textarea input type=text name=\"invoice_itemised_note\" rows=10 cols=70 WRAP=nowrap>$inv_noteField</textarea></td>
			</tr>
	                <tr>
	                         <td class='details_screen'>$LANG_tax</td><td>$display_block_tax</td>
	                </tr>
	                         <td class='details_screen'>$LANG_inv_pref</td><td>$display_block_preferences/td>
	                </tr>
		";
	}
	
	
	#END - if itemised style show the invoice note field
}
#END INVOICE ITEMISED/CONSULTING SECTION



$display_block_bottom =  "
        
        </table>
	<!-- addition close table tag to close invoice itemised/consulting if it has a note -->
	</table>

";


?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
	<script type="text/javascript" src="./include/jquery.js"></script>

	<script type="text/javascript" src="include/jquery.dom_creator.js"></script>
	<script type="text/javascript" src="include/jquery.datePicker.js"></script>
	<script type="text/javascript" src="include/jquery.datePicker.conf.js"></script>

	<link rel="stylesheet" type="text/css" href="include/jquery.datePicker.css" title="default" media="screen" />

	<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
	<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>

<?php include('./config/config.php'); ?> 
<body>
<b>You are editing <?php echo $pref_inv_wordingField; ?> <?php echo $master_invoice_id; ?></b>

 <hr></hr>

<FORM name="frmpost" ACTION="index.php?module=invoices&view=save" METHOD=POST>

<?php echo $display_block_top; ?>
<?php echo $display_block_details; ?>
<?php echo $display_block_bottom; ?>

<hr></hr>
	<input type=button value='Cancel'onCLick='history.back()'>
	<input type=submit name="submit" value="<?php echo $LANG_save; ?>">
	<input type=hidden name="max_items" value="<?php echo $line; ?>">
</form>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
