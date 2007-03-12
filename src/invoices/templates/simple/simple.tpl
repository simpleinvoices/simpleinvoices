<?php


$invoice_summary =  "

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
	
	$show_custom_field_1
	$show_custom_field_2
	$show_custom_field_3 
	$show_custom_field_4 
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

?>
