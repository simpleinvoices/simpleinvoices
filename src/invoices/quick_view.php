<?php
include("./include/include_main.php");

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$master_invoice_id = $_GET['submit'];



#master invoice id select
$print_master_invoice_id = "SELECT * FROM {$tb_prefix}invoices WHERE inv_id = $master_invoice_id";
$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

while ($Array_master_invoice = mysql_fetch_array($result_print_master_invoice_id)) {
                $inv_idField = $Array_master_invoice['inv_id'];
                $inv_biller_idField = $Array_master_invoice['inv_biller_id'];
                $inv_customer_idField = $Array_master_invoice['inv_customer_id'];
                $inv_typeField = $Array_master_invoice['inv_type'];
                $inv_preferenceField = $Array_master_invoice['inv_preference'];
		$inv_dateField = date( $config['date_format'], strtotime( $Array_master_invoice['inv_date'] ) );
		$calc_dateField = date('Y-m-d', strtotime( $Array_master_invoice['inv_date'] ) );
                $inv_noteField = $Array_master_invoice['inv_note'];
                $inv_custom_field1Field = $Array_master_invoice['invoice_custom_field1'];
                $inv_custom_field2Field = $Array_master_invoice['invoice_custom_field2'];
                $inv_custom_field3Field = $Array_master_invoice['invoice_custom_field3'];
                $inv_custom_field4Field = $Array_master_invoice['invoice_custom_field4'];

};


#invoice_type query

        $sql_invoice_type = "SELECT inv_ty_description FROM {$tb_prefix}invoice_type WHERE inv_ty_id = $inv_typeField";

        $result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

        while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
                $inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];
	};

#customer query
$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE c_id = $inv_customer_idField";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

#biller query
$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE b_id = $inv_biller_idField";
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
$print_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_id = $inv_preferenceField ";
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
$print_defaults = "SELECT * FROM {$tb_prefix}defaults WHERE def_id = 1";
$result_print_defaults = mysql_query($print_defaults, $conn) or die(mysql_error());


while ($Array_defaults = mysql_fetch_array($result_print_defaults) ) {
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
                $def_inv_templateField = $Array_defaults['def_inv_template'];
};



#Accounts - for the invoice - start
#invoice total total - start
	$invoice_total_Field = calc_invoice_total($inv_idField);
	$invoice_total_Field_format = number_format($invoice_total_Field,2);
#invoice total total - end

#amount paid calc - start
	$invoice_paid_Field = calc_invoice_paid($inv_idField);
	$invoice_paid_Field_format = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
	$invoice_owing_Field = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end
#Acounts - for invoce - end


#Accounts - for the customer - start
#invoice total calc - start
	$invoice_total_Field_customer = calc_customer_total($c_idField);
	$invoice_total_Field_customer_format = number_format($invoice_total_Field_customer,2);
#invoice total calc - end

#amount paid calc - start
   	$invoice_paid_Field_customer = calc_customer_paid($c_idField);
        $invoice_paid_Field_customer_format = number_format($invoice_paid_Field_customer,2);
#amount paid calc - end

#amount owing calc - start
        $invoice_owing_Field_customer = number_format($invoice_total_Field_customer - $invoice_paid_Field_customer,2);
#amount owing calc - end

        #Invoice Age - number of days - start
        if ($invoice_owing_Field > 0 ) {
                $invoice_age_days = (strtotime(date('Y-m-d')) - strtotime($calc_dateField)) / (60 * 60 * 24);
                /*$invoice_age_days = (strtotime(date("Y-m-d")) - strtotime($inv_dateField)) / (60 * 60 * 24);*/
                         $invoice_age = "$invoice_age_days $LANG_days";
        }
        else {
                $invoice_age ="";
        }

        #Invoice Age - number of days - start


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


$show_custom_field_1 = show_custom_field(invoice_cf1,$inv_custom_field1Field,read,'details_screen summary','','',5,':');
$show_custom_field_2 = show_custom_field(invoice_cf2,$inv_custom_field2Field,read,'details_screen summary','','',5,':');
$show_custom_field_3 = show_custom_field(invoice_cf3,$inv_custom_field3Field,read,'details_screen summary','','',5,':');
$show_custom_field_4 = show_custom_field(invoice_cf4,$inv_custom_field4Field,read,'details_screen summary','','',5,':');

#START INVOICE HERE - TOP SECTION

$display_block_top =  "
	<table align=center>
	<tr>
		<td class=account colspan=8>$LANG_account_info</td><td width=5%></td><td class=\"columnleft\" width=5%></td><td class=\"account\" colspan=6><a href='index.php?module=customers&view=details&submit=$c_idField&action=view'>$LANG_customer_account</a></td>
	</tr>
	<tr>
		<td class=account>$LANG_total:</td><td class=account>$pref_currency_signField$invoice_total_Field_format</td>
		<td class=account><a href='index.php?module=payments&view=manage&inv_id=$inv_idField'>$LANG_paid:</a></td><td class=account>$pref_currency_signField$invoice_paid_Field_format</td>
		<td class=account>$LANG_owing:</td><td class=account><u>$pref_currency_signField$invoice_owing_Field</u></td>
		<td class=account>$LANG_age:</td><td class=account nowrap >$invoice_age <a href='documentation/info_pages/age.html' rel='gb_page_center[450, 450]'><img src=\"./images/common/help-small.png\"></img></a></td>
		<td></td><td class=\"columnleft\"></td>
		<td class=\"account\">$LANG_total:</td><td class=account>$pref_currency_signField$invoice_total_Field_customer_format</td>
		<td class=account><a href='index.php?module=payments&view=manage&c_id=$c_idField'>$LANG_paid:</a></td><td class=account>$pref_currency_signField$invoice_paid_Field_customer_format</td>
		<td class=account>$LANG_owing:</td><td class=account><u>$pref_currency_signField$invoice_owing_Field_customer</u></td>
	</tr>
	</table>


	<table align=center>
	<tr>
		<td colspan=6 align=center class='align_center'><b>$pref_inv_headingField</b></td>
	</tr>
        <tr>
                <td colspan=6><br></td>
        </tr>

	<!-- Invoice Summary section -->

	<tr class='details_screen'>
		<td><b>$pref_inv_wordingField $LANG_summary:</b></td><td colspan=5 align=right class='align_right'><a href='#' class=\"show-summary\" onClick=\"$('.summary').show();$('.show-summary').hide();\">$LANG_show_details</a><a href='#' class=\"summary\" onClick=\"$('.summary').hide();$('.show-summary').show();\">$LANG_hide_details</a> </td>
	</tr>
	<tr class='details_screen summary'>
		<td>$pref_inv_wordingField $LANG_number_short:</td><td colspan=5>$inv_idField</td>
	</tr>
	<tr class='details_screen summary'>
		<td>$pref_inv_wordingField $LANG_date:</td><td colspan=5>$inv_dateField</td>
	</tr>
	$show_custom_field_1 
	$show_custom_field_2 
	$show_custom_field_3 
	$show_custom_field_4
	<tr>	
		<td><br></td>
	</tr>
	<!-- Biller section -->
	<tr class='details_screen'>
		<td><b>$LANG_biller:</b></td><td colspan=3>$b_nameField</b></td><td colspan=2 class='align_right' align=right><a href='#' class=\"show-biller\" onClick=\"$('.biller').show();$('.show-biller').hide();\">$LANG_show_details</a><a href='#' class=\"biller\" onClick=\"$('.biller').hide();$('.show-biller').show();\">$LANG_hide_details</a></td>
	</tr>
	<tr class='details_screen biller'>
		<td>$LANG_street:</td><td colspan=5>$b_street_addressField</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$LANG_street2:</td><td colspan=5>$b_street_address2Field</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$LANG_city:</td><td colspan=3>$b_cityField</td><td>$LANG_phone_short:</td><td>$b_phoneField</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$LANG_state, Zip:</td><td colspan=3>$b_stateField, $b_zip_codeField</td><td>$LANG_mobile_short:</td><td>$b_mobile_phoneField</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$LANG_country:</td><td colspan=3>$b_countryField</td><td>$LANG_fax:</td><td>$b_faxField</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$LANG_email:</td><td colspan=5>$b_emailField</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$biller_custom_field_label1:</td><td colspan=5>$b_custom_field1Field</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$biller_custom_field_label2:</td><td colspan=5>$b_custom_field2Field</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$biller_custom_field_label3:</td><td colspan=5>$b_custom_field3Field</td>
	</tr>	
	<tr class='details_screen biller'>
		<td>$biller_custom_field_label4:</td><td colspan=5>$b_custom_field4Field</td>
	</tr>	
	<tr >
		<td colspan=5><br></td>
	</tr>	
	
	<!-- Customer section -->
	<tr class='details_screen'
		<td><b>$LANG_customer:</b></td><td colspan=3>$c_nameField</td><td colspan=2 align=right class='align_right'><a href='#' class=\"show-customer\" onClick=\"$('.customer').show(); $('.show-customer').hide(); \">$LANG_show_details</a> <a href='#' class=\"customer\" onClick=\"$('.customer').hide(); $('.show-customer').show();\">$LANG_hide_details</a></td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$LANG_attention_short:</td><td colspan=5 align=left>$c_attentionField,</td>
	</tr>
	<tr class='details_screen customer'>
		<td>$LANG_street:</td><td colspan=5 align=left>$c_street_addressField</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$LANG_street2:</td><td colspan=5 align=left>$c_street_address2Field</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$LANG_city:</td><td colspan=3>$c_cityField</td><td>Ph:</td><td>$c_phoneField</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$LANG_state, ZIP:</td><td colspan=3>$c_stateField, $c_zip_codeField</td><td>$LANG_fax:</td><td>$c_faxField</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$LANG_country:</td><td colspan=3>$c_countryField</td><td>Mobile:</td><td>$c_mobile_phoneField</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$LANG_email:<td colspan=5>$c_emailField</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$customer_custom_field_label1:</td><td colspan=5>$c_custom_field1Field</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$customer_custom_field_label2:</td><td colspan=5>$c_custom_field2Field</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$customer_custom_field_label3:</td><td colspan=5>$c_custom_field3Field</td>
	</tr>	
	<tr class='details_screen customer'>
		<td>$customer_custom_field_label4:</td><td colspan=5>$c_custom_field4Field</td>
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
                $inv_it_tax_amountField = number_format($Array_master_invoice_items['inv_it_tax_amount'],2);
                $inv_it_gross_totalField = number_format($Array_master_invoice_items['inv_it_gross_total'],2);
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = number_format($Array_master_invoice_items['inv_it_total'],2);

	};

	#products query
	$print_products = "SELECT * FROM {$tb_prefix}products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $Array['prod_id'];
                $prod_descriptionField = $Array['prod_description'];
                $prod_unit_priceField = $Array['prod_unit_price'];

	};

	#invoice_total total query
	$invoice_total_totalField = calc_invoice_total($master_invoice_id);
	$invoice_total_totalField_formatted = number_format($invoice_total_totalField,2);
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
        	        <td></td><td></td><td></td><td>$pref_currency_signField$inv_it_gross_totalField</td><td>$pref_currency_signField$inv_it_tax_amountField</td><td><u>$pref_currency_signField$inv_it_totalField</u></td>
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
                                <td colspan=5></td>
                                <td class='details_screen'><a href='#' align=right class=\"show-itemised\" onClick=\"$('.itemised').show();$('.show-itemised').hide();\">$LANG_show_details</a><a href='#' class=\"itemised\" onClick=\"$('.itemised').hide();$('.show-itemised').show();\">$LANG_hide_details</a> 
                        <tr>
			<tr>
        		        <td><b>$LANG_quantity_short</b></td><td><b>$LANG_description</b></td><td><b>$LANG_unit_price</b><td><b>$LANG_gross_total</b></td><td><b>$LANG_tax</b></td><td><b>$LANG_total_uppercase</b></td>
		        </tr>";
	}
	#show column heading for consulting style
        else if ( $_GET['invoice_style'] === 'Consulting' ) {
                $display_block_details .=  "
		<tr>
		<td colspan=6>
		<table width=100%> 
			<tr>
				<td colspan=6></td>
				<td class='details_screen'><a href='#' align=right class=\"show-consulting\" onClick=\"$('.consulting').show();$('.show-consulting').hide();\">$LANG_show_details</a><a href='#' class=\"consulting\" onClick=\"$('.consulting').hide();$('.show-consulting').show();\">$LANG_hide_details</a> 
        	        <tr>
               	 	       <td><b>$LANG_quantity_short</b></td><td><b>$LANG_item</b></td><td class=show-consulting><b>$LANG_description</b></td><td class='consulting'></td><td><b>$LANG_unit_price</b><td><b>$LANG_gross_total</b></td><td><b>$LANG_tax</b></td><td align=right><b>$LANG_total_uppercase</b></td>
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
	$print_products = "SELECT * FROM {$tb_prefix}products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $Array['prod_id'];
                $prod_descriptionField = $Array['prod_description'];
                $prod_unit_priceField = $Array['prod_unit_price'];
	        $prod_custom_field1Field = $Array['prod_custom_field1'];
       		$prod_custom_field2Field = $Array['prod_custom_field2'];
	        $prod_custom_field3Field = $Array['prod_custom_field3'];
       		$prod_custom_field4Field = $Array['prod_custom_field4'];

	$invoice_total_totalField = calc_invoice_total($master_invoice_id);
	$invoice_total_totalField_formatted = number_format($invoice_total_totalField,2);

	$invoice_total_taxField = calc_invoice_tax($master_invoice_id);
	$invoice_total_taxField_formatted = number_format($invoice_total_taxField,2);

	#calculation for each line item
	$gross_total_itemised = $prod_unit_priceField * $inv_it_quantityField ;

	#calculation for the Invoice Total

	#MERGE ITEMISED AND CONSULTING HERE
	#PRINT the line items
	#show the itemised invoice
	if ( $_GET['invoice_style'] === 'Itemised' ) {

		$display_block_details .=  "
	        <tr>
	                <td>$inv_it_quantityField</td><td>$prod_descriptionField</td><td>$pref_currency_signField$inv_it_unit_priceField_formatted</td><td>$pref_currency_signField$inv_it_gross_totalField_formatted</td><td>$pref_currency_signField$inv_it_tax_amountField_formatted</td><td>$pref_currency_signField$inv_it_totalField_formatted</td>
	        </tr>
                <tr  class='itemised' >       
                        <td></td>
				<td colspan=5>
					<table width=100%>
					<tr>
						<td width=50% class='details_screen'>$prod_custom_field_label1: $prod_custom_field1Field</td><td width=50% class='details_screen'>$prod_custom_field_label2: $prod_custom_field2Field</td>
                 			</tr>
			                <tr class='itemised' >       
			                       <td width=50% class='details_screen'>$prod_custom_field_label3: $prod_custom_field3Field</td><td width=50% class='details_screen'>$prod_custom_field_label4: $prod_custom_field4Field</td>
			                 </tr>
					</table>
				</td>
		</tr>
		";
	}	
	#show the consulting invoice 
	else if ( $_GET['invoice_style'] === 'Consulting' ) {
		
	        #item description - only show first 20 characters and add ... to signify theres more text
	        $max_length = 20;
	        if (strlen($inv_it_descriptionField) > $max_length ) {
	                $stripped_item_description = substr($inv_it_descriptionField,0,20);
	                $stripped_item_description .= "...";
	        }
	        else if (strlen($inv_it_descriptionField) <= $max_length ) {
	                 $stripped_item_description = $inv_it_descriptionField;
	        }

	        $display_block_details .=  "
        	<tr>
	                <td>$inv_it_quantityField</td><td>$prod_descriptionField</td><td class='show-consulting'>$stripped_item_description</td><td class='consulting'></td><td>$pref_currency_signField$inv_it_unit_priceField_formatted</td><td>$pref_currency_signField$inv_it_gross_totalField_formatted</td><td>$pref_currency_signField$inv_it_tax_amountField_formatted</td><td align=right>$pref_currency_signField$inv_it_totalField_formatted</td>
		</tr>
		<tr  class='consulting' >	
                        <td></td>
                                <td colspan=6>
                                        <table width=100%>
                                        <tr>
                                                <td width=50% class='details_screen'>$prod_custom_field_label1: $prod_custom_field1Field</td><td width=50% class='details_screen'>$prod_custom_field_label2: $prod_custom_field2Field</td>
                                        </tr>
                                        <tr>       
                                               <td width=50% class='details_screen'>$prod_custom_field_label3: $prod_custom_field3Field</td><td width=50% class='details_screen'>$prod_custom_field_label4: $prod_custom_field4Field</td>
                                         </tr>
                                        </table>
                                </td>
	<!--		<td></td><td colspan=6 class='details_screen consulting'>$prod_custom_field_label1: $prod_custom_field1Field, $prod_custom_field_label2: $prod_custom_field2Field, $prod_custom_field_label3: $prod_custom_field3Field, $prod_custom_field_label4: $prod_custom_field4Field</td> -->
		 </tr>";
		if ($inv_it_descriptionField != null) {
			$display_block_details .=  "
			<tr  class='consulting' >	
				<td></td><td colspan=6 class='details_screen consulting'>$LANG_description:<br>$inv_it_descriptionField</td>
			 </tr>
			";
		}
	}




	};
	};

	#if itemised style show the invoice note field - START
	if ( $_GET['invoice_style'] === 'Itemised' && !empty($inv_noteField) OR 'Consulting' && !empty($inv_noteField)) {
                #item description - only show first 20 characters and add ... to signify theres more text
                $max_length = 20;
                if (strlen($inv_noteField) > $max_length ) {
                        $stripped_itemised_note = substr($inv_noteField,0,20);
                        $stripped_itemised_note .= "...";
                }
                else if (strlen($inv_noteField) <= $max_length ) {
                         $stripped_itemised_note = $inv_noteField;
                }


		$display_block_details .=  "
			</table>
			</td></tr>
			<tr>
				<td></td>
			</tr>
			<tr class='details_screen'>
				<td colspan=5><b>$LANG_notes:</b></td><td align=right class='details_screen'><a href='#' align=right class=\"show-notes\" onClick=\"$('.notes').show();$('.show-notes').hide();\">$LANG_show_details</a><a href='#' class=\"notes\" onClick=\"$('.notes').hide();$('.show-notes').show();\">$LANG_hide_details</a> 
</td>
			</tr>
			<!-- if hide detail click - the stripped note will be displayed -->
			<tr class='show-notes details_screen'>
				<td colspan=6>$stripped_itemised_note</td>
			</tr>
			<!-- if show detail click - the full note will be displayed -->
			<tr class='notes details_screen'>
				<td colspan=6>$inv_noteField</td>
			</tr>
		";
	}
	
	
	#END - if itemised style show the invoice note field

	$display_block_details .=  "
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

";


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript">
	$(document).ready(function() {
	 // hides the customer and biller details as soon as the DOM is ready (a little sooner that page load)
	  $('.show-summary').hide();
	  $('.biller').hide();
	  $('.customer').hide();
	  $('.consulting').hide();
	  $('.itemised').hide();
	  $('.notes').hide();
  	});
    </script>


	<title><?php echo $title; ?></title>
<?php include('./config/config.php'); ?> 
<body>
<?php echo $LANG_quick_view_of; echo " "; echo $pref_inv_wordingField; ?> <?php echo $master_invoice_id; ?>
<br>
	<?php 
	$url_pdf = "$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$inv_idField&action=view&location=pdf&invoice_style=$inv_ty_descriptionField";
	$url_pdf_encoded = urlencode($url_pdf); 
	$url_for_pdf = "pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&URL=$url_pdf_encoded";


	?>
<!--Actions heading - start-->
<?php echo $LANG_actions;?>: 
		<a href="index.php?module=invoices&view=templates/template&submit=<?php echo $inv_idField; ?>&action=view&invoice_style=<?php echo $inv_ty_descriptionField;?>"> <?php echo $LANG_print_preview;?></a>
		 :: 
		<a href="index.php?module=invoices&view=details&submit=<?php echo $inv_idField; ?>&action=view&invoice_style=<?php echo $inv_ty_descriptionField;?>"> <?php echo $LANG_edit; ?></a>
		 ::
		 <a href='index.php?module=payments&view=process&submit=<?php echo $inv_idField;?>&op=pay_selected_invoice'> <?php echo $LANG_process_payment; ?> </a>
		 ::
		 <!-- EXPORT TO PDF -->
		<a href='<?php echo $url_for_pdf ;?>'><?php echo $LANG_export_pdf;?></a>
		::
		<a href="index.php?module=invoices&view=templates/template&submit=<?php echo $inv_idField; ?>&action=view&invoice_style=<?php echo $inv_ty_descriptionField;?>&export=<?php echo $spreadsheet;?>"><?php echo $LANG_export_as; echo " ."; echo $spreadsheet;?></a>
		::
		<a href="index.php?module=invoices&view=templates/template&submit=<?php echo $inv_idField; ?>&action=view&invoice_style=<?php echo $inv_ty_descriptionField;?>&export=<?php echo $word_processor;?>"><?php echo $LANG_export_as; echo " ."; echo $word_processor;?> </a>
		:: 
		<?php echo $LANG_email; echo " :: "; echo $LANG_email_quick; ?>
<!--Actions heading - start-->
<hr></hr>
</form>
<!-- #PDF end -->


<?php echo $display_block_top; ?>
<hr></hr>
<?php echo $display_block_details; ?>
<?php echo $display_block_bottom; ?>

<hr></hr>
	<form>
		<input type=button value='<?php echo $LANG_cancel;?>'onCLick='history.back()'>
	</form>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
