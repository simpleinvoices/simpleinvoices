<?php
include("./include/include_print.php");
include("./include/functions.php");

#get the invoice id
$master_invoice_id = $_GET['submit'];

#Info from DB print
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

#master invoice id select
$print_master_invoice_id = 'SELECT * FROM si_invoices WHERE inv_id = ' . $master_invoice_id;
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

/*
		$inv_it_total_tax_amount = $inv_it_gross_totalField / $inv_it_taxField  ;
                $inv_dateField = $Array_master_invoice['inv_date'];
*/


#invoice_type query

        $sql_invoice_type = 'SELECT inv_ty_description FROM si_invoice_type WHERE inv_ty_id = ' . $inv_typeField;

        $result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

        while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
                $inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];
	};

#customer query
$print_customer = "SELECT * FROM si_customers WHERE c_id = $inv_customer_idField";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

#biller query
$print_biller = "SELECT * FROM si_biller WHERE b_id = $inv_biller_idField";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_print_customer)) {
                $c_idField = $Array['c_id'];
                $c_attentionField = $Array['c_attention'];
                $c_nameField = $Array['c_name'];
                $c_street_addressField = $Array['c_street_address'];
	        $c_street_address2Field = $Array['c_street_address2'];
                $c_cityField = $Array['c_city'];
                $c_stateField = $Array['c_state'];
                $c_zip_codeField = $Array['c_zip_code'];
                $c_countryField = $Array['c_country'];
		$c_phoneField = $Array['c_phone'];
	        $c_mobile_phoneField = $Array['c_mobile_phone'];
		$c_faxField = $Array['c_fax'];
		$c_emailField = $Array['c_email'];
	        $c_custom_field1Field = $Array['c_custom_field1'];
       		$c_custom_field2Field = $Array['c_custom_field2'];
	        $c_custom_field3Field = $Array['c_custom_field3'];
	        $c_custom_field4Field = $Array['c_custom_field4'];

};

while ($billerArray = mysql_fetch_array($result_print_biller)) {
                $b_idField = $billerArray['b_id'];
                $b_nameField = $billerArray['b_name'];
                $b_street_addressField = $billerArray['b_street_address'];
                $b_street_address2Field = $billerArray['b_street_address2'];
                $b_cityField = $billerArray['b_city'];
                $b_stateField = $billerArray['b_state'];
                $b_zip_codeField = $billerArray['b_zip_code'];
                $b_countryField = $billerArray['b_country'];
                $b_phoneField = $billerArray['b_phone'];
                $b_mobile_phoneField = $billerArray['b_mobile_phone'];
                $b_faxField = $billerArray['b_fax'];
                $b_emailField = $billerArray['b_email'];
                $b_custom_field1Field = $billerArray['b_custom_field1'];
                $b_custom_field2Field = $billerArray['b_custom_field2'];
                $b_custom_field3Field = $billerArray['b_custom_field3'];
                $b_custom_field4Field = $billerArray['b_custom_field4'];
                $b_co_footerField = $billerArray['b_co_footer'];
                $b_co_logoField = $billerArray['b_co_logo'];
};


#preferences query
$print_preferences = "SELECT * FROM si_preferences where pref_id = $inv_preferenceField ";
$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());

while ($Array_preferences = mysql_fetch_array($result_print_preferences)) {
                $pref_idField = $Array_preferences['pref_id'];
                $pref_descriptionField = $Array_preferences['pref_description'];
                $pref_currency_signField = $Array_preferences['pref_currency_sign'];
                $pref_inv_headingField = $Array_preferences['pref_inv_heading'];
                $pref_inv_wordingField = $Array_preferences['pref_inv_wording'];
                $pref_inv_detail_headingField = $Array_preferences['pref_inv_detail_heading'];
                $pref_inv_detail_lineField = $Array_preferences['pref_inv_detail_line'];
                $pref_inv_payment_methodField = $Array_preferences['pref_inv_payment_method'];
                $pref_inv_payment_line1_nameField = $Array_preferences['pref_inv_payment_line1_name'];
                $pref_inv_payment_line1_valueField = $Array_preferences['pref_inv_payment_line1_value'];
                $pref_inv_payment_line2_nameField = $Array_preferences['pref_inv_payment_line2_name'];
                $pref_inv_payment_line2_valueField = $Array_preferences['pref_inv_payment_line2_value'];

};


#system defaults query
$print_defaults = "SELECT * FROM si_defaults WHERE def_id = 1";
$result_print_defaults = mysql_query($print_defaults, $conn) or die(mysql_error());


while ($Array_defaults = mysql_fetch_array($result_print_defaults) ) {
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
                $def_inv_templateField = $Array_defaults['def_inv_template'];
};



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

#get custom field labels for biller
$biller_custom_field_label1 = get_custom_field_label(biller_cf1);
$biller_custom_field_label2 = get_custom_field_label(biller_cf2);
$biller_custom_field_label3 = get_custom_field_label(biller_cf3);
$biller_custom_field_label4 = get_custom_field_label(biller_cf4);
#get custom field labels for the customer
$customer_custom_field_label1 = get_custom_field_label(customer_cf1);
$customer_custom_field_label2 = get_custom_field_label(customer_cf2);
$customer_custom_field_label3 = get_custom_field_label(customer_cf3);
$customer_custom_field_label4 = get_custom_field_label(customer_cf4);
#product custom fields
$prod_custom_field_label1 = get_custom_field_label(product_cf1);
$prod_custom_field_label2 = get_custom_field_label(product_cf2);
$prod_custom_field_label3 = get_custom_field_label(product_cf3);
$prod_custom_field_label4 = get_custom_field_label(product_cf4);


#START INVOICE HERE - TOP SECTION



$display_block_top =  "

	<table align=center>
	<!--
	<tr>
		<td colspan=6 align=center><b>$pref_inv_headingField</b></td>
	</tr>
        <tr>
                <td colspan=6><br></td>
        </tr>
	-->
	<!-- Invoice Summary section -->

	<tr class='details_screen'>
		<td><b>$pref_inv_wordingField $LANG_summary:</b></td>
	</tr>
	<tr class='details_screen summary'>
		<td>$pref_inv_wordingField $LANG_number_short:</td><td colspan=5>$inv_idField</td>
	</tr>
	<tr class='details_screen summary'>
		<td>$pref_inv_wordingField date:</td><td colspan=5>$inv_dateField</td>
	</tr>
	<tr>	
		<td><br></td>
	</tr>
	<!-- Biller section -->
	<tr class='details_screen'>
		<td><b>$LANG_biller:</b></td><td colspan=3>$b_nameField</b></td>
	</tr>
	";
        if ($b_street_addressField != null) {
                $display_block_top .=  "
		<tr class='details_screen biller'>
			<td>$LANG_address:</td><td colspan=5>$b_street_addressField</td>
		</tr>	
		";
	}	
        if ($b_street_address2Field != null) {
                $display_block_top .=  "
		<tr class='details_screen biller'>
			<td></td><td colspan=5>$b_street_address2Field</td>
		</tr>		
		";
	}	
	/*merged address section start*/
        if ($b_cityField != null OR $b_stateField != null OR $b_zip_codeField != null) {
                $display_block_top .=  "<tr><td></td><td colspan=3>";
        }
        if ($b_cityField != null) {
                $display_block_top .=  "$b_cityField";
	}	

	if ($b_cityField != null AND $b_stateField != null  ) {
                $display_block_top .=  ", ";
        }

       	if ($b_stateField != null) {
                $display_block_top .=  "$b_stateField";
	}	

	if (($b_cityField != null OR $b_stateField != null) AND ($b_zip_codeField != null)) {
                $display_block_top .=  ", ";
        }

	if ($b_zip_codeField != null) {
                $display_block_top .=  "$b_zip_codeField";
	}	

	/*merged address line end*/
       
	/*country field start*/
	 if ($b_countryField != null) {
                $display_block_top .=  "
		</tr>
		<tr>
			<td></td><td>$b_countryField</td>
		</tr>
		";
	}	
	/*country field end*/

	/*phone details start*/
	if ($b_phoneField != null OR $b_phoneField != null OR $b_mobile_phoneField != null) {
		$display_block_top .=  "<tr>";
	}

	if ($b_phoneField != null) {
                $display_block_top .=  "<td>$LANG_phone_short:</td><td>$b_phoneField</td>";
		$tr++;
        } 
	if ($b_faxField != null) {
                $display_block_top .=  "<td>$LANG_fax:</td><td>$b_faxField</td>";
		$tr++;
		$display_block_top .= do_tr($tr,'blank-class');

        } 
	if ($b_mobile_phoneField != null) {
                $display_block_top .=  "<td>$LANG_mobile_short:</td><td>$b_mobile_phoneField</td>";
		$tr++;
		$display_block_top .= do_tr($tr,'blank-class');
        } 
	/*phone details end*/
        $display_block_top .= print_if_not_null($LANG_email, $b_emailField,'blank','blank',5);
        $display_block_top .= print_if_not_null($biller_custom_field_label1, $b_custom_field1Field,'blank','blank',5);
        $display_block_top .= print_if_not_null($biller_custom_field_label2, $b_custom_field2Field,'blank','blank',5);
        $display_block_top .= print_if_not_null($biller_custom_field_label3, $b_custom_field3Field,'blank','blank',5);
        $display_block_top .= print_if_not_null($biller_custom_field_label4, $b_custom_field4Field,'blank','blank',5);

$display_block_top .=  "
	<tr >
		<td colspan=5><br></td>
	</tr>	
	
	<!-- Customer section -->
	<tr class='details_screen'
		<td><b>$LANG_customer:</b></td><td colspan=3>$c_nameField</td>
	</tr>	
	";
        if ($c_attentionField != null) {
                $display_block_top .=  "
		<tr class='details_screen customer'>
			<td>$LANG_attention_short:</td><td colspan=5 align=left>$c_attentionField,</td>
		</tr>
		";
	}
        if ($c_street_addressField != null) {
                $display_block_top .=  "
		<tr class='details_screen customer'>
			<td>$LANG_address:</td><td colspan=5 align=left>$c_street_addressField</td>
		</tr>	
		";
	}
        if ($c_street_address2Field != null) {
                $display_block_top .=  "
		<tr class='details_screen customer'>";
		if ($c_street_addressField == null) {
                $display_block_top .=  "
                        <td>$LANG_address:</td><td colspan=5 align=left>$c_street_address2Field</td>
                </tr>   
                ";
		}
                if ($c_street_addressField != null) {
                $display_block_top .=  "
			<td></td><td colspan=5 align=left>$c_street_address2Field</td>
		</tr>	
		";
		}
	}
	
	$customer_merged_address = merge_address($c_cityField, $c_stateField, $c_zip_codeField, $c_street_addressField, $c_street_address2Field,'blank','blank',3);
	$display_block_top .= $customer_merged_address;

        /*country field start*/
         if ($c_countryField != null) {
                $display_block_top .=  "
                <tr>
                        <td></td><td>$c_countryField</td>
                </tr>
                ";
        }       
        /*country field end*/

        /*phone details start*/
        if ($c_phoneField != null OR $c_phoneField != null OR $c_mobile_phoneField != null) {
                $display_block_top .=  "<tr>";
        }

        if ($c_phoneField != null) {
                $display_block_top .=  "<td>$LANG_phone_short:</td><td>$c_phoneField</td>";
                $tr_c++;
        } 
        if ($c_faxField != null) {
                $display_block_top .=  "<td>$LANG_fax:</td><td>$c_faxField</td>";
                $tr_c++;
                $display_block_top .= do_tr($tr_c,'blank-class');

        } 
        if ($c_mobile_phoneField != null) {
                $display_block_top .=  "<td>$LANG_mobile_short:</td><td>$c_mobile_phoneField</td>";
                $tr_c++;
                $display_block_top .= do_tr($tr_c,'blank-class');
        }
        /*phone details start*/

	$display_block_top .= print_if_not_null($LANG_email, $c_emailField,'blank','blank',5);
	$display_block_top .= print_if_not_null($customer_custom_field_label1, $c_custom_field1Field,'blank','blank',5);
	$display_block_top .= print_if_not_null($customer_custom_field_label2, $c_custom_field2Field,'blank','blank',5);
	$display_block_top .= print_if_not_null($customer_custom_field_label3, $c_custom_field3Field,'blank','blank',5);
	$display_block_top .= print_if_not_null($customer_custom_field_label4, $c_custom_field4Field,'blank','blank',5);
/*
        if ($c_emailField != null) {
                $display_block_top .=  "
		<tr>
			<td>$LANG_email:<td colspan=5>$c_emailField</td>
		</tr>	
		";
	}

        if ($c_custom_field1Field != null) {
                $display_block_top .=  "
		<tr>
			<td>$customer_custom_field_label1:</td><td colspan=5>$c_custom_field1Field</td>
		</tr>	
		";
	}
        if ($c_custom_field2Field != null) {
                $display_block_top .=  "
		<tr>
			<td>$customer_custom_field_label2:</td><td colspan=5>$c_custom_field2Field</td>
		</tr>	
		";
	}
        if ($c_custom_field3Field != null) {
                $display_block_top .=  "
		<tr>
			<td>$customer_custom_field_label3:</td><td colspan=5>$c_custom_field3Field</td>
		</tr>	
		";
	}
        if ($c_custom_field4Field != null) {
                $display_block_top .=  "
		<tr>
			<td>$customer_custom_field_label4:</td><td colspan=5>$c_custom_field4Field</td>
		</tr>	
		";
	}
*/
#PRINT DETAILS FOR THE TOTAL STYLE INVOICE

if (  $_GET['invoice_style'] === 'Total' ) {
        #invoice total layout - no quantity

	#get all the details for the total style
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM si_invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
                $inv_it_idField = $Array_master_invoice_items['inv_it_id'];
                $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
                $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
                $inv_it_quantityField_formatted = number_format($Array_master_invoice_items['inv_it_quantity'],2);
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_unit_priceField_formatted = number_format($Array_master_invoice_items['inv_it_unit_price'],2);
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_tax_amountField_formatted = number_format($Array_master_invoice_items['inv_it_tax_amount'],2);
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_gross_totalField_formatted = number_format($Array_master_invoice_items['inv_it_gross_total'],2);
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];
                $inv_it_totalField_formatted = number_format($Array_master_invoice_items['inv_it_total'],2);

	};

	#products query
	$print_products = "SELECT * FROM si_products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $Array['prod_id'];
                $prod_descriptionField = $Array['prod_description'];
                $prod_unit_priceField = $Array['prod_unit_price'];
	};

	#invoice_total total query
	$print_invoice_total_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_total = mysql_query($print_invoice_total_total, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_invoice_total_total)) {
                $invoice_total_totalField = $Array['total'];
                $invoice_total_totalField_formatted = number_format($Array['total'],2);

	};
	#all the details have bee got now print them to screen

	$display_block_details =  "

	        <tr>
	                <td colspan=6><br></td>
        	</tr>
	        <tr>
        	        <td colspan=6><b>$LANG_description</b></td>
	        </tr>
	        <tr>
	                <td colspan=6>$inv_it_descriptionField</td>
        	</tr>
	        <tr>
        	        <td colspan=6><br></td>
	        </tr>
	        <tr>
	                <td></td><td></td><td></td><td><b>$LANG_gross_total</b></td><td><b>$LANG_tax</b></td><td><b>$LANG_total_uppercase</b></td>
        	</tr>
	        <tr>
        	        <td></td><td></td><td></td><td>$pref_currency_signField$inv_it_gross_totalField_formatted</td><td>$pref_currency_signField$inv_it_tax_amountField_formatted</td><td><u>$pref_currency_signField$inv_it_totalField_formatted</u></td>
	        </tr>

        	<tr>
                	<td colspan=6><br><br></td>
	        </tr>
        	<tr>
                	<td colspan=6><b>$pref_inv_detail_headingField</b></td>
	        </tr>
	";	
   

     }

#INVOICE ITEMEISED and CONSULTING SECTION

else if ( $_GET['invoice_style'] === 'Itemised' || $_GET['invoice_style'] === 'Consulting' ) {

	$display_block_details =  "
        <tr>
                <td colspan=6><br></td>
        </tr>
	";
	
	#show column heading for itemised style
        if ( $_GET['invoice_style'] === 'Itemised' ) {
		$display_block_details .=  "      
		<tr>
		<td colspan=6>
		<table width=100%>
		<tr>
        	        <td><b>$LANG_quantity_short</b></td><td><b>$LANG_description</b></td><td><b>$LANG_unit_price</b><td><b>$LANG_gross_total</b></td><td><b>$LANG_tax</b></td><td align=right><b>$LANG_total_uppercase</b></td>
	        </tr>";
	}
	#show column heading for consulting style
        else if ( $_GET['invoice_style'] === 'Consulting' ) {
                $display_block_details .=  "
		<tr>
		<td colspan=6>
		<table>
                <tr>
                        <td><b>$LANG_quantity_short</b></td><td><b>$LANG_item</b></td><td><b>$LANG_unit_price</b><td><b>$LANG_gross_total</b></td><td><b>$LANG_tax</b></td><td align=right><b>$LANG_total_uppercase</b></td>
                </tr>";
        }




	#INVOIVE_ITEMS SECTION
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM si_invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
                $inv_it_idField = $Array_master_invoice_items['inv_it_id'];
                $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
                $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
                $inv_it_quantityField_formatted = number_format($Array_master_invoice_items['inv_it_quantity'],2);
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_unit_priceField_formatted = number_format($Array_master_invoice_items['inv_it_unit_price'],2);
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_tax_amountField_formatted = number_format($Array_master_invoice_items['inv_it_tax_amount'],2);
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_gross_totalField_formatted = number_format($Array_master_invoice_items['inv_it_gross_total'],2);
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];
                $inv_it_totalField_formatted = number_format($Array_master_invoice_items['inv_it_total'],2);
	/*
	};
	*/

	#products query
	$print_products = "SELECT * FROM si_products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($productArray = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $productArray['prod_id'];
                $prod_descriptionField = $productArray['prod_description'];
                $prod_unit_priceField = $productArray['prod_unit_price'];
                $prod_custom_field1Field = $productArray['prod_custom_field1'];
                $prod_custom_field2Field = $productArray['prod_custom_field2'];
                $prod_custom_field3Field = $productArray['prod_custom_field3'];
                $prod_custom_field4Field = $productArray['prod_custom_field4'];

	/*
	};
	*/

	#invoice_total total query
	$print_invoice_total_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_total = mysql_query($print_invoice_total_total, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_invoice_total_total)) {
                $invoice_total_totalField = $Array['total'];
                $invoice_total_totalField_formatted = number_format($Array['total'],2);

	#invoice total tax
	$print_invoice_total_tax ="select sum(inv_it_tax_amount) as total_tax from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_tax = mysql_query($print_invoice_total_tax, $conn) or die(mysql_error());

	while ($Array_tax = mysql_fetch_array($result_print_invoice_total_tax)) {
                $invoice_total_taxField = $Array_tax['total_tax'];
                $invoice_total_taxField_formatted = number_format($Array_tax['total_tax'],2);


	/*
	};
	*/	


	#calculation for each line item
	$gross_total_itemised = $prod_unit_priceField * $inv_it_quantityField ;
	/*
	$tax_per_item =  $prod_unit_priceField / $inv_it_taxField;
	$total_tax_per_line = $tax_per_item  * $inv_it_quantityField ;
	$total_per_line = $gross_total_itemised + $total_tax_per_line ;
	*/

	#calculation for the Invoice Total

	#MERGE ITEMISED AND CONSULTING HERE
	#PRINT the line items
	#show the itemised invoice
	if ( $_GET['invoice_style'] === 'Itemised' ) {

		$display_block_details .=  "
	        <tr>
	                <td>$inv_it_quantityField_formatted</td><td>$prod_descriptionField</td><td>$pref_currency_signField$inv_it_unit_priceField_formatted</td><td>$pref_currency_signField$inv_it_gross_totalField_formatted</td><td>$pref_currency_signField$inv_it_tax_amountField_formatted</td><td align=right>$pref_currency_signField$inv_it_totalField_formatted</td>
	        </tr>
                <tr>       
                        <td></td><td colspan=5>
						<table width=100%>
							<tr>
		";
		/*Get the custom fields and show them nicely*/
		$display_block_details .= inv_itemised_cf($prod_custom_field_label1, $prod_custom_field1Field);
		$inv_it_tr++;
		$display_block_details .= do_tr($inv_it_tr,'blank-class');	
		$display_block_details .= inv_itemised_cf($prod_custom_field_label2, $prod_custom_field2Field);
		$inv_it_tr++;
		$display_block_details .= do_tr($inv_it_tr,'blank-class');	
		$display_block_details .= inv_itemised_cf($prod_custom_field_label3, $prod_custom_field3Field);
		$inv_it_tr++;
		$display_block_details .= do_tr($inv_it_tr,'blank-class');	
		$display_block_details .= inv_itemised_cf($prod_custom_field_label4, $prod_custom_field4Field);
		$inv_it_tr++;
		$display_block_details .= do_tr($inv_it_tr,'blank-class');	
		$inv_it_tr = 0;

                $display_block_details .=  " 
							</tr>
						</table>
				</td>
		</tr>

		";
	}	
	#show the consulting invoice 
	else if ( $_GET['invoice_style'] === 'Consulting' ) {
		
	        $display_block_details .=  "
        	<tr>
	                <td>$inv_it_quantityField_formatted</td><td>$prod_descriptionField</td><td>$pref_currency_signField$inv_it_unit_priceField_formatted</td><td>$pref_currency_signField$inv_it_gross_totalField_formatted</td><td>$pref_currency_signField$inv_it_tax_amountField_formatted</td><td align=right>$pref_currency_signField$inv_it_totalField_formatted</td>
		</tr>
                <tr>       
                        <td></td><td colspan=6>
                                                <table width=100%>
                                                        <tr>
                ";
                /*Get the custom fields and show them nicely*/
                $display_block_details .= inv_itemised_cf($prod_custom_field_label1, $prod_custom_field1Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');    
                $display_block_details .= inv_itemised_cf($prod_custom_field_label2, $prod_custom_field2Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');    
                $display_block_details .= inv_itemised_cf($prod_custom_field_label3, $prod_custom_field3Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');    
                $display_block_details .= inv_itemised_cf($prod_custom_field_label4, $prod_custom_field4Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');    
                $inv_it_tr = 0;

                $display_block_details .=  " 
                                                        </tr>
                                                </table>
                                </td>
                 </tr>
		";
		if ($inv_it_descriptionField != null) {
			$display_block_details .=  "
			<tr>
				<td></td><td colspan=6><i>$LANG_description</i>: $inv_it_descriptionField</td>
			</tr>";
		}
/*	
		$display_block_details .=  "
		<tr>
			<td></td><td></td><td>$pref_currency_signField$inv_it_unit_priceField</td><td>$pref_currency_signField$inv_it_gross_totalField</td><td>$pref_currency_signField$inv_it_tax_amountField</td><td>$pref_currency_signField$inv_it_totalField</td>
        	</tr>
		";
*/
	}




	};
	};
	};
	};

	#if itemised style show the invoice note field - START
	if ( $_GET['invoice_style'] === 'Itemised' && !empty($inv_noteField) OR 'Consulting' && !empty($inv_noteField)) {

		$display_block_details .=  "
			</table>
			</td></tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td colspan=7><b>$LANG_notes:</b></td>
			</tr>
			<tr>
				<td colspan=7>$inv_noteField</td>
			</tr>
		";
	}
	
	
	#END - if itemised style show the invoice note field

	$display_block_details .=  "
	<!--
        <tr>
                <td colspan=3 align=left>$LANG_totals</td><td>$pref_currency_signField$invoice_total_taxField</td><td><u>$pref_currency_signField$invoice_total_taxField</u></td><td><u>$pref_currency_signField$invoice_total_totalField</u></td>

        </tr>
	-->
	<tr>
		<td colspan=6><br></td>
	</tr>	

        <tr>
                <td colspan=3></td><td align=left colspan=2>$LANG_total $LANG_tax $LANG_included</td><td colspan=2 align=right>$pref_currency_signField$invoice_total_taxField_formatted</td>
        </tr>
	<tr><td><br></td>
	</tr>
        <tr>
                <td colspan=3></td><td align=left colspan=2><b>$pref_inv_wordingField $LANG_amount</b></td><td colspan=2 align=right><u>$pref_currency_signField$invoice_total_totalField_formatted</u></td>
        </tr>


	<tr>
		<td colspan=6><br><br></td>
	</tr>	
	<tr>
		<td colspan=6><b>$pref_inv_detail_headingField</b></td>
	</tr>
	";
}
#END INVOICE ITEMISED/CONSULTING SECTION



$display_block_bottom =  "
        <tr>
                <td colspan=6><i>$pref_inv_detail_lineField</i></td>
        </tr>
	<tr>
		<td colspan=6>$pref_inv_payment_methodField</td>
        <tr>
                <td>$pref_inv_payment_line1_nameField</td><td colspan=5>$pref_inv_payment_line1_valueField</td>
        </tr>
        <tr>
                <td>$pref_inv_payment_line2_nameField</td><td colspan=5>$pref_inv_payment_line2_valueField</td>
        </tr>
        </table>
	<!-- addition close table tag to close invoice itemised/consulting if it has a note -->
	</table>
        <br><br>
        <div style=font-size:8pt align=center >$b_co_footerField
	</div>
";

/* The Export code - supports any file extensions - excel/word/open office - what reads html */
if (isset($_GET['export'])) {
$file_extension = $_GET['export'];
header("Content-type: application/octet-stream");
/*header("Content-type: application/x-msdownload");*/
header("Content-Disposition: attachment; filename=$pref_inv_headingField$inv_idField.$file_extension");
header("Pragma: no-cache");
header("Expires: 0");
}
/* End Export code */


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="./include/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="../themes/<?php echo $theme; ?>/print.css">
</head>
	<title><?php echo $title; ?></title>
<body>

<br>

<?php echo $display_block_top; ?>
<?php echo $display_block_details; ?>
<?php echo $display_block_bottom; ?>


</div>

</body>
</html>
