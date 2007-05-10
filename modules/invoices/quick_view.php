<?php

checkLogin();

#get the invoice id
$master_invoice_id = $_GET['submit'];

$invoice = getInvoice($master_invoice_id);
$invoice_type =  getInvoiceType($invoice['type_id']);
$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);
$preference = getPreference($invoice['preference_id']);
$defaults = getSystemDefaults();




#Accounts - for the invoice - start
#invoice total total - start
	$invoice_total_Field = calc_invoice_total($invoice['id']);
	$invoice_total_Field_format = number_format($invoice_total_Field,2);
#invoice total total - end

#amount paid calc - start
	$invoice_paid_Field = calc_invoice_paid($invoice['id']);
	$invoice_paid_Field_format = number_format($invoice_paid_Field,2);
<div id="left">
#amount paid calc - end

#amount owing calc - start
	$invoice_owing_Field = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end
#Acounts - for invoce - end


#Accounts - for the customer - start
#invoice total calc - start
	$invoice_total_Field_customer = calc_customer_total($customer['id']);
	$invoice_total_Field_customer_format = number_format($invoice_total_Field_customer,2);
#invoice total calc - end

#amount paid calc - start
   	$invoice_paid_Field_customer = calc_customer_paid($customer['id']);
        $invoice_paid_Field_customer_format = number_format($invoice_paid_Field_customer,2);
#amount paid calc - end

#amount owing calc - start
        $invoice_owing_Field_customer = number_format($invoice_total_Field_customer - $invoice_paid_Field_customer,2);
#amount owing calc - end

        #Invoice Age - number of days - start
        if ($invoice_owing_Field > 0 ) {
                $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
                /*$invoice_age_days = (strtotime(date("Y-m-d")) - strtotime($invoice[date_field])) / (60 * 60 * 24);*/
                         $invoice_age = "$invoice_age_days {$LANG['days']}";
        }
        else {
                $invoice_age ="";
        }

        #Invoice Age - number of days - start


#get custom field labels for biller
$billerCustomFieldLabel = getCustomFieldLabels("biller");
$customerCustomFieldLabel = getCustomFieldLabels("customer");
$productCustomFieldLabel = getCustomFieldLabels("product");


$show_custom_field_1 = show_custom_field(invoice_cf1,$invoice['invoice_custom_field1'],read,'details_screen summary','','',5,':');
$show_custom_field_2 = show_custom_field(invoice_cf2,$invoice['invoice_custom_field2'],read,'details_screen summary','','',5,':');
$show_custom_field_3 = show_custom_field(invoice_cf3,$invoice['invoice_custom_field3'],read,'details_screen summary','','',5,':');
$show_custom_field_4 = show_custom_field(invoice_cf4,$invoice['invoice_custom_field4'],read,'details_screen summary','','',5,':');

#START INVOICE HERE - TOP SECTION

$display_block_top =  <<<EOD
	<table align=center>
	<tr>
		<td class=account colspan=8>{$LANG['account_info']}</td><td width=5%></td><td class="columnleft" width=5%></td><td class="account" colspan=6><a href='index.php?module=customers&view=details&submit=$customer[id]&action=view'>{$LANG['customer_account']}</a></td>
	</tr>
	<tr>
		<td class=account>{$LANG['total']}:</td><td class=account>$preference[pref_currency_sign]$invoice_total_Field_format</td>
		<td class=account><a href='index.php?module=payments&view=manage&id=$invoice[id]'>{$LANG['paid']}:</a></td><td class=account>$preference[pref_currency_sign]$invoice_paid_Field_format</td>
		<td class=account>{$LANG['owing']}:</td><td class=account><u>$preference[pref_currency_sign]$invoice_owing_Field</u></td>
		<td class=account>{$LANG['age']}:</td><td class=account nowrap >$invoice_age <a href='docs.php?p=age&t=help' rel='gb_page_center[450, 450]'><img src="./images/common/help-small.png"></img></a></td>
		<td></td><td class="columnleft"></td>
		<td class="account">{$LANG['total']}:</td><td class=account>$preference[pref_currency_sign]$invoice_total_Field_customer_format</td>
		<td class=account><a href='index.php?module=payments&view=manage&id=$customer[id]'>{$LANG['paid']}:</a></td><td class=account>$preference[pref_currency_sign]$invoice_paid_Field_customer_format</td>
		<td class=account>{$LANG['owing']}:</td><td class=account><u>$preference[pref_currency_sign]$invoice_owing_Field_customer</u></td>
	</tr>
	</table>


	<table align=center>
	<tr>
		<td colspan=6 align=center class="align_center"><b>$preference[pref_inv_heading]</b></td>
	</tr>
        <tr>
                <td colspan=6><br></td>
        </tr>

	<!-- Invoice Summary section -->

	<tr class='details_screen'>
		<td class='details_screen'><b>$preference[pref_inv_wording] {$LANG['summary']}:</b></td><td colspan=5 align=right class='details_screen align_right'><a href='#' class="show-summary" onClick="$('.summary').show();$('.show-summary').hide();">{$LANG['show_details']}</a><a href='#' class="summary" onClick="$('.summary').hide();$('.show-summary').show();">{$LANG['hide_details']}</a> </td>
	</tr>
	<tr class='details_screen summary'>
		<td class='details_screen'>$preference[pref_inv_wording] {$LANG['number_short']}:</td><td colspan=5 class='details_screen'>$invoice[id]</td>
	</tr>
	<tr class='details_screen summary'>
		<td>$preference[pref_inv_wording] {$LANG['date']}:</td><td colspan=5>$invoice[date_field]</td>
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
		<td class='details_screen'><b>{$LANG['biller']}:</b></td><td colspan=3>$biller[name]</b></td><td colspan=2 class='align_right' align=right><a href='#' class="show-biller" onClick="$('.biller').show();$('.show-biller').hide();">{$LANG['show_details']}</a><a href='#' class="biller" onClick="$('.biller').hide();$('.show-biller').show();">{$LANG['hide_details']}</a></td>
	</tr>
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG['street']}:</td><td class='details_screen' colspan=5>$biller[street_address]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG['street2']}:</td><td class='details_screen' colspan=5>$biller[street_address2]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG['city']}:</td><td class='details_screen' colspan=3>$biller[city]</td><td class='details_screen'>{$LANG['phone_short']}:</td><td class='details_screen'>$biller[phone]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG['state']}, Zip:</td><td colspan=3>$biller[state], $biller[zip_code]</td><td class='details_screen'>{$LANG['mobile_short']}:</td><td class='details_screen'>$biller[mobile_phone]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG['country']}:</td><td class='details_screen' colspan=3>$biller[country]</td><td class='details_screen'>{$LANG['fax']}:</td><td class='details_screen'>$biller[fax]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG['email']}:</td><td class='details_screen' colspan=5>$biller[email]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$billerCustomFieldLabel['1']}:</td><td class='details_screen' colspan=5>$biller[b_custome_field1]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$billerCustomFieldLabel['2']}:</td><td class='details_screen' colspan=5>$biller[b_custome_field2]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$billerCustomFieldLabel['3']}:</td><td class='details_screen' colspan=5>$biller[b_custome_field3]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$billerCustomFieldLabel['4']}:</td><td class='details_screen' colspan=5>$biller[b_custome_field4]</td>
	</tr>	
	<tr >
		<td colspan=5><br></td>
	</tr>	
	
	<!-- Customer section -->
	<tr class='details_screen'
		<td class='details_screen'><b>{$LANG['customer']}:</b></td><td colspan=3>$customer[name]</td><td colspan=2 align=right class='details_screen align_right'><a href='#' class="show-customer" onClick="$('.customer').show(); $('.show-customer').hide(); ">{$LANG['show_details']}</a> <a href='#' class="customer" onClick="$('.customer').hide(); $('.show-customer').show();">{$LANG['hide_details']}</a></td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG['attention_short']}:</td><td class='details_screen' colspan=5 align=left>$customer[attention],</td>
	</tr>
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG['street']}:</td><td class='details_screen' colspan=5 align=left>$customer[street_address]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG['street2']}:</td><td class='details_screen' colspan=5 align=left>$customer[stree_address2]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG['city']}:</td><td class='details_screen' colspan=3>$customer[city]</td><td class='details_screen'>Ph:</td><td>$customer[phone]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG['state']}, ZIP:</td><td colspan=3 class='details_screen'>$customer[state], $customer[zip_code]</td><td class='details_screen'>{$LANG['fax']}:</td><td class='details_screen'>$customer[fax]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG['country']}:</td><td class='details_screen' colspan=3>$customer[country]</td><td class='details_screen'>Mobile:</td><td class='details_screen'>$customer[mobile_phone]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG['email']}:</td><td class='details_screen'colspan=5>$customer[email]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customerCustomFieldLabel['1']}:</td><td colspan=5 class='details_screen'>$customer[custom_field1]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customerCustomFieldLabel['2']}:</td><td colspan=5 class='details_screen'>$customer[custom_field2]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customerCustomFieldLabel['3']}:</td><td class='details_screen' colspan=5>$customer[custom_field3]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customerCustomFieldLabel['4']}:</td><td class='details_screen' colspan=5>$customer[custom_field4]</td>
	</tr>	

EOD;

#PRINT DETAILS FOR THE TOTAL STYLE INVOICE

if (  $_GET['invoice_style'] === 'Total' ) {
        #invoice total layout - no quantity

	#get all the details for the total style
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM {$tb_prefix}invoice_items WHERE  inv_it_invoice_id = $master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	$invoice_items = mysql_fetch_array($result_print_master_invoice_items);
	$invoice_items['inv_it_tax_amount'] = number_format($invoice_items['inv_it_tax_amount'],2);
	$invoice_items['inv_it_gross_total'] = number_format($invoice_items['inv_it_gross_total'],2);
    $invoice_items['inv_it_total'] = number_format($invoice_items['inv_it_total'],2);



	#invoice_total total query
	$invoice_total_totalField = calc_invoice_total($master_invoice_id);
	$invoice_total_totalField_formatted = number_format($invoice_total_totalField,2);
	#all the details have bee got now print them to screen

	$display_block_details =  <<<EOD

	        <tr>
	                <td colspan=6><br></td>
        	</tr>
	        <tr>
        	        <td colspan=6><b>{$LANG['description']}</b></td>
	        </tr>
	        <tr>
	                <td colspan=6>$invoice_items[inv_it_description]</td>
        	</tr>
	        <tr>
        	        <td colspan=6><br></td>
	        </tr>
	        <tr>
	                <td></td><td></td><td></td><td><b>{$LANG['gross_total']}</b></td><td><b>{$LANG['tax']}</b></td><td><b>{$LANG['total_uppercase']}</b></td>
        	</tr>
	        <tr>
        	        <td></td><td></td><td></td><td>$preference[pref_currency_sign]$invoice_items[inv_it_gross_total]</td><td>$preference[pref_currency_sign]$invoice_items[inv_it_tax_amount]</td><td><u>$preference[pref_currency_sign]$invoice_items[inv_it_total]</u></td>
	        </tr>

        	<tr>
                	<td colspan=6><br><br></td>
	        </tr>
        	<tr>
                	<td colspan=6><b>$preference[pref_inv_detail_heading]</b></td>
	        </tr>
EOD;
   

     }

#INVOICE ITEMEISED and CONSULTING SECTION

else if ( $_GET['invoice_style'] === 'Itemised' || $_GET['invoice_style'] === 'Consulting' ) {

	$display_block_details = <<<EOD
        <tr>
                <td colspan=6><br></td>
        </tr>
EOD;
	
	#show column heading for itemised style
        if ( $_GET['invoice_style'] === 'Itemised' ) {
        	
		$display_block_details .=  <<<EOD
		<tr>
		<td colspan=6>
		<table width=100%>
                        <tr>
                                <td colspan=5></td>
                                <td class='details_screen'><a href='#' align=right class="show-itemised" onClick="$('.itemised').show();$('.show-itemised').hide();">{$LANG['show_details']}</a><a href='#' class="itemised" onClick="$('.itemised').hide();$('.show-itemised').show();">{$LANG['hide_details']}</a> 
                        <tr>
			<tr>
        		        <td><b>{$LANG['quantity_short']}</b></td><td><b>{$LANG['description']}</b></td><td><b>{$LANG['unit_price']}</b><td><b>{$LANG['gross_total']}</b></td><td><b>{$LANG['tax']}</b></td><td><b>{$LANG['total_uppercase']}</b></td>
		        </tr>
EOD;
	}
	#show column heading for consulting style
        else if ( $_GET['invoice_style'] === 'Consulting' ) {
                $display_block_details .=  <<<EOD
		<tr>
		<td colspan=6>
		<table width=100%> 
			<tr>
				<td colspan=6></td>
				<td class='details_screen'><a href='#' align=right class="show-consulting" onClick="$('.consulting').show();$('.show-consulting').hide();">{$LANG['show_details']}</a><a href='#' class="consulting" onClick="$('.consulting').hide();$('.show-consulting').show();">{$LANG['hide_details']}</a> 
        	        <tr>
               	 	       <td><b>{$LANG['quantity_short']}</b></td><td><b>{$LANG['item']}</b></td><td class=show-consulting><b>{$LANG['description']}</b></td><td class='consulting'></td><td><b>{$LANG['unit_price']}</b><td><b>{$LANG['gross_total']}</b></td><td><b>{$LANG['tax']}</b></td><td align=right><b>{$LANG['total_uppercase']}</b></td>
	                </tr>
EOD;
        }




	#INVOIVE_ITEMS SECTION
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM {$tb_prefix}invoice_items WHERE  inv_it_invoice_id = $master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());

	while($invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
		$invoice_items['inv_it_unit_price'] = number_format($invoice_items['inv_it_unit_price'],2);
		$invoice_items['inv_it_tax_amount'] = number_format($invoice_items['inv_it_tax_amount'],2);
		$invoice_items['inv_it_gross_total'] = number_format($invoice_items['inv_it_gross_total'],2);
	    $invoice_items['inv_it_total'] = number_format($invoice_items['inv_it_total'],2);
	
	
		#products query
		$print_products = "SELECT * FROM {$tb_prefix}products WHERE id = $invoice_items[inv_it_product_id]";
		$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());
	
		
		while ($product = mysql_fetch_array($result_print_products)) { 
	
			$invoice_total_totalField = calc_invoice_total($master_invoice_id);
			$invoice_total_totalField_formatted = number_format($invoice_total_totalField,2);
		
			$invoice_total_taxField = calc_invoice_tax($master_invoice_id);
			$invoice_total_taxField_formatted = number_format($invoice_total_taxField,2);
		
			#calculation for each line item
			$gross_total_itemised = $product['unit_price'] * $invoice_items['inv_it_quantity'] ;
		
			#calculation for the Invoice Total
	
			#MERGE ITEMISED AND CONSULTING HERE
			#PRINT the line items
			#show the itemised invoice
			if ( $_GET['invoice_style'] === 'Itemised' ) {
	
			$display_block_details .=  <<<EOD
		        <tr>
	                <td>$invoice_items[inv_it_quantity]</td><td>$product[description]</td><td>$preference[pref_currency_sign]$invoice_items[inv_it_unit_price]</td><td>$preference[pref_currency_sign]$invoice_items[inv_it_gross_total]</td><td>$preference[pref_currency_sign]$invoice_items[inv_it_tax_amount]</td><td>$invoice_items[inv_it_total]</td>
	        </tr>
                <tr  class='itemised' >       
                        <td></td>
				<td colspan=5>
					<table width=100%>
					<tr>
						<td width=50% class='details_screen'>{$productCustomFieldLabel['1']}: $product[custom_field1]</td><td width=50% class='details_screen'>{$productCustomFieldLabel['2']}:
$product[custom_field2]</td>
                 			</tr>
			                <tr class='itemised' >       
			                       <td width=50% class='details_screen'>{$productCustomFieldLabel['3']}:
	$product[custom_field3]</td><td width=50% class='details_screen'>{$productCustomFieldLabel['4']}:
	$product[custom_field4]</td>
			                 </tr>
					</table>
				</td>
		</tr>
EOD;
	}	
	#show the consulting invoice 
	else if ( $_GET['invoice_style'] === 'Consulting' ) {
		
	        #item description - only show first 20 characters and add ... to signify theres more text
	        $max_length = 20;
	        if (strlen($invoice_items['inv_it_description']) > $max_length ) {
	                $stripped_item_description = substr($invoice_items['inv_it_description'],0,20);
	                $stripped_item_description .= "...";
	        }
	        else if (strlen($invoice_items['inv_it_description']) <= $max_length ) {
	                 $stripped_item_description = $invoice_items['inv_it_description'];
	        }

	        $display_block_details .=  <<<EOD
        	<tr>
	                <td>$invoice_items[inv_it_quantity]</td><td>$product[description]</td><td class='show-consulting'>$stripped_item_description</td><td class='consulting'></td><td>$preference[pref_currency_sign]$invoice_items[inv_it_unit_price]</td><td>$preference[pref_currency_sign]$invoice_items[inv_it_gross_total]</td><td>$preference[pref_currency_sign]$invoice_items[inv_it_tax_amount]</td><td align=right>$preference[pref_currency_sign]$invoice_items[inv_it_total]</td>
		</tr>
		<tr  class='consulting' >	
                        <td></td>
                                <td colspan=6>
                                        <table width=100%>
                                        <tr>
                                                <td width=50% class='details_screen'>$prod_custom_field_label1: $product[custom_field1]</td><td width=50% class='details_screen'>$prod_custom_field_label2: $product[custom_field2]</td>
                                        </tr>
                                        <tr>       
                                               <td width=50% class='details_screen'>$prod_custom_field_label3: $product[custom_field3]</td><td width=50% class='details_screen'>$prod_custom_field_label4: $product[custom_field4]</td>
                                         </tr>
                                        </table>
                                </td>
	<!--		<td></td><td colspan=6 class='details_screen consulting'>$prod_custom_field_label1: $product[custom_field1], $prod_custom_field_label2: $product[custom_field2], $prod_custom_field_label3: $product[custom_field3], $prod_custom_field_label4: $product[custom_field4]</td> -->
		 </tr>
EOD;
		if ($invoice_items['inv_it_description'] != null) {
			$display_block_details .= <<<EOD
			<tr  class='consulting' >	
				<td></td><td colspan=6 class='details_screen consulting'>{$LANG['description']}:<br>$invoice_items[inv_it_description]</td>
			 </tr>
EOD;
		}
	}




	};
	};

	#if itemised style show the invoice note field - START
	if ( $_GET['invoice_style'] === 'Itemised' && !empty($invoice['note']) OR 'Consulting' && !empty($invoice['note'])) {
                #item description - only show first 20 characters and add ... to signify theres more text
                $max_length = 20;
                if (strlen($invoice['note']) > $max_length ) {
                        $stripped_itemised_note = substr($invoice['note'],0,20);
                        $stripped_itemised_note .= "...";
                }
                else if (strlen($invoice['note']) <= $max_length ) {
                         $stripped_itemised_note = $invoice['note'];
                }


		$display_block_details .=  <<<EOD
			</table>
			</td></tr>
			<tr>
				<td></td>
			</tr>
			<tr class='details_screen'>
				<td colspan=5><b>{$LANG['notes']}:</b></td><td align=right class='details_screen'><a href='#' align=right class="show-notes" onClick="$('.notes').show();$('.show-notes').hide();">{$LANG['show_details']}</a><a href='#' class="notes" onClick="$('.notes').hide();$('.show-notes').show();">{$LANG['hide_details']}</a> 
</td>
			</tr>
			<!-- if hide detail click - the stripped note will be displayed -->
			<tr class='show-notes details_screen'>
				<td colspan=6>$stripped_itemised_note</td>
			</tr>
			<!-- if show detail click - the full note will be displayed -->
			<tr class='notes details_screen'>
				<td colspan=6>$invoice[note]</td>
			</tr>
EOD;
	}
	
	
	#END - if itemised style show the invoice note field

	$display_block_details .=  <<<EOD
	<tr>
		<td colspan=6><br></td>
	</tr>	

        <tr>
                <td colspan=3></td><td align=left colspan=2>{$LANG['total']} {$LANG['tax']} {$LANG['included']}</td><td colspan=2 align=right>$preference[pref_currency_sign]$invoice_total_taxField_formatted</td>
        </tr>
	<tr><td><br></td>
	</tr>
        <tr>
                <td colspan=3></td><td align=left colspan=2><b>$preference[pref_inv_wording] {$LANG['amount']}</b></td><td colspan=2 align=right><u>$preference[pref_currency_sign]$invoice_total_totalField_formatted</u></td>
        </tr>


	<tr>
		<td colspan=6><br><br></td>
	</tr>	
	<tr>
		<td colspan=6><b>$preference[pref_inv_detail_heading]</b></td>
	</tr>
EOD;
}
#END INVOICE ITEMISED/CONSULTING SECTION



$display_block_bottom =  <<<EOD
        <tr>
                <td colspan=6><i>$preference[pref_inv_detail_line]</i></td>
        </tr>
	<tr>
		<td colspan=6>$preference[pref_inv_payment_method]</td>
        <tr>
                <td>$preference[pref_inv_payment_line1_name]</td><td colspan=5>$preference[pref_inv_payment_line1_value]</td>
        </tr>
        <tr>
                <td>$preference[pref_inv_payment_line2_name]</td><td colspan=5>$preferenece[pref_inv_payment_line2_value]</td>
        </tr>
        </table>
	<!-- addition close table tag to close invoice itemised/consulting if it has a note -->
	</table>
EOD;

include('./config/config.php');


	$url_pdf = "$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$invoice[id]&action=view&location=pdf&invoice_style=$invoice_type[inv_ty_description]";
	$url_pdf_encoded = urlencode($url_pdf); 
	$url_for_pdf = "./pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&pdfname=$preference[pref_inv_wording]$invoice[id]&URL=$url_pdf_encoded";
	
	
echo <<<EOD
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script
	type="text/javascript" src="./include/jquery/jquery.js"></script>
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


	<title>{$title}</title>
<body>
{$LANG[quick_view_of]} {$preference[pref_inv_wording]} {$master_invoice_id}
<br>



<!--Actions heading - start-->
{$LANG[actions]}: 
		<a href="index.php?module=invoices&view=templates/template&submit={$invoice[id]}&action=view&invoice_style={$invoice_type[inv_ty_description]}"> {$LANG['print_preview']}</a>
		 :: 
		<a href="index.php?module=invoices&view=details&submit={$invoice[id]}&action=view&invoice_style={$invoice_type[inv_ty_description]}"> {$LANG[edit]}</a>
		 ::
		 <a href='index.php?module=payments&view=process&submit={$invoice[id]}&op=pay_selected_invoice'> {$LANG[process_payment]} </a>
		 ::
		 <!-- EXPORT TO PDF -->
		<a href='{$url_for_pdf }'>{$LANG[export_pdf]}</a>
		::
		<a href="index.php?module=invoices&view=templates/template&submit={$invoice[id]}&action=view&invoice_style={$invoice_type[inv_ty_description]}&export={$spreadsheet}">{$LANG[export_as]} .{$spreadsheet}</a>
		::
		<a href="index.php?module=invoices&view=templates/template&submit={$invoice[id]}&action=view&invoice_style={$invoice_type[inv_ty_description]}&export={$word_processor}">{$LANG[export_as]} .{$word_processor} </a>
		:: <a href="index.php?module=invoices&view=email&stage=1&submit={$invoice[id]}">{$LANG[email]}</a>
<!--Actions heading - start-->
<hr></hr>
</form>
<!-- #PDF end -->


{$display_block_top}
<hr></hr>
{$display_block_details}
{$display_block_bottom}

<hr></hr>
	<form>
		<input type=button value="{$LANG[cancel]}" onCLick="history.back()">
	</form>
EOD;

?>
