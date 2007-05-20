<?php

checkLogin();

#get the invoice id
$invoiceID = $_GET['submit'];

$invoice = getInvoice($invoiceID);
$invoice_type =  getInvoiceType($invoice['type_id']);
$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);
$preference = getPreference($invoice['preference_id']);
$defaults = getSystemDefaults();
$invoiceItems = getInvoiceItems($invoiceID);

#Invoice Age - number of days - start
if ($invoice['owing'] > 0 ) {
    $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
	$invoice_age = "$invoice_age_days {$LANG['days']}";
}
else {
    $invoice_age ="";
}

#Invoice Age - number of days - start


$customFieldLabels = getCustomFieldLabels();

//TODO...
$show_custom_field_1 = show_custom_field("invoice_cf1",$invoice['custom_field1'],"read",'details_screen summary','','',5,':');
$show_custom_field_2 = show_custom_field("invoice_cf2",$invoice['custom_field2'],"read",'details_screen summary','','',5,':');
$show_custom_field_3 = show_custom_field("invoice_cf3",$invoice['custom_field3'],"read",'details_screen summary','','',5,':');
$show_custom_field_4 = show_custom_field("invoice_cf4",$invoice['custom_field4'],"read",'details_screen summary','','',5,':');

#START INVOICE HERE - TOP SECTION


#PRINT DETAILS FOR THE TOTAL STYLE INVOICE


if (  $_GET['invoice_style'] === 'Total' ) {

	$display_block_details =  <<<EOD

	        <tr>
	                <td colspan=6><br></td>
        	</tr>
	        <tr>
        	        <td colspan=6><b>{$LANG['description']}</b></td>
	        </tr>
	        <tr>
	                <td colspan=6>{$invoiceItems[0]['description']}</td>
        	</tr>
	        <tr>
        	        <td colspan=6><br></td>
	        </tr>
	        <tr>
	                <td></td><td></td><td></td><td><b>{$LANG['gross_total']}</b></td><td><b>{$LANG['tax']}</b></td><td><b>{$LANG['total_uppercase']}</b></td>
        	</tr>
	        <tr>
        	        <td></td><td></td><td></td><td>$preference[pref_currency_sign]{$invoiceItems[0]['gross_total']}</td><td>$preference[pref_currency_sign]{$invoiceItems[0]['tax_amount']}</td><td><u>$preference[pref_currency_sign]{$invoiceItems[0]['total']}</u></td>
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
		<td colspan="6">
		<table width="100%">
                        <tr>
                                <td colspan="5"></td>
                                <td class="details_screen"><a href='#' align=right class="show-itemised" onClick="$('.itemised').show();$('.show-itemised').hide();">{$LANG['show_details']}</a><a href='#' class="itemised" onClick="$('.itemised').hide();$('.show-itemised').show();">{$LANG['hide_details']}</a> 
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


		foreach($invoiceItems as $invoiceItem) {

		if ( $_GET['invoice_style'] === 'Itemised' ) {
	
			$display_block_details .=  <<<EOD
		        <tr>
	                <td>$invoiceItem[quantity]</td><td>{$invoiceItem['product']['description']}</td><td>$preference[pref_currency_sign]$invoiceItem[unit_price]</td><td>$preference[pref_currency_sign]$invoiceItem[gross_total]</td><td>$preference[pref_currency_sign]$invoiceItem[tax_amount]</td><td>$invoiceItem[total]</td>
	        </tr>
                <tr  class='itemised' >       
                        <td></td>
				<td colspan=5>
					<table width=100%>
					<tr>
						<td width=50% class='details_screen'>{$customFieldLabels['product_cf1']}: {$invoiceItem['product']['custom_field1']}</td><td width=50% class='details_screen'>{$customFieldLabels['product_cf2']}:
{$invoiceItem['product']['custom_field2']}</td>
                 			</tr>
			                <tr class='itemised' >       
			                       <td width=50% class='details_screen'>{$customFieldLabels['product_cf3']}:
	{$invoiceItem['product']['custom_field3']}</td><td width=50% class='details_screen'>{$customFieldLabels['product_cf4']}:
	{$invoiceItem['product']['custom_field4']}</td>
			                 </tr>
					</table>
				</td>
		</tr>
EOD;
		
	}	
	
	#show the consulting invoice 
	if ( $_GET['invoice_style'] === 'Consulting' ) {
		
	        #item description - only show first 20 characters and add ... to signify theres more text
	        $max_length = 20;
	        if (strlen($invoiceItem['description']) > $max_length ) {
	                $stripped_item_description = substr($invoiceItem['description'],0,20);
	                $stripped_item_description .= "...";
	        }
	        else if (strlen($invoiceItem['description']) <= $max_length ) {
	                 $stripped_item_description = $invoiceItem['description'];
	        }

	        $display_block_details .=  <<<EOD
        	<tr>
	                <td>$invoiceItem[quantity]</td><td>{$invoiceItem['product']['description']}</td><td class='show-consulting'>$stripped_item_description</td><td class='consulting'></td><td>$preference[pref_currency_sign]$invoiceItem[unit_price]</td><td>$preference[pref_currency_sign]$invoiceItem[gross_total]</td><td>$preference[pref_currency_sign]$invoiceItem[tax_amount]</td><td align=right>$preference[pref_currency_sign]$invoiceItem[total]</td>
		</tr>
		<tr  class='consulting' >	
                        <td></td>
                                <td colspan=6>
                                        <table width=100%>
                                        <tr>
                                                <td width=50% class='details_screen'>{$customFieldLabels['product_cf1']}: {$invoiceItem['product']['custom_field1']}</td><td width=50% class='details_screen'>{$customFieldLabels['product_cf2']}: {$invoiceItem['product']['custom_field2']}</td>
                                        </tr>
                                        <tr>       
                                               <td width=50% class='details_screen'>{$customFieldLabels['product_cf3']}: {$invoiceItem['product']['custom_field3']}</td><td width=50% class='details_screen'>{$customFieldLabels['product_cf4']}: {$invoiceItem['product']['custom_field4']}</td>
                                         </tr>
                                        </table>
                                </td>
	<!--		<td></td><td colspan=6 class='details_screen consulting'>$prod_custom_field_label1: $product[custom_field1], $prod_custom_field_label2: $product[custom_field2], $prod_custom_field_label3: $product[custom_field3], $prod_custom_field_label4: $product[custom_field4]</td> -->
		 </tr>
EOD;
		if ($invoiceItem['description'] != null) {
			$display_block_details .= <<<EOD
			<tr  class='consulting' >	
				<td></td><td colspan=6 class='details_screen consulting'>{$LANG['description']}:<br>$invoiceItem[description]</td>
			 </tr>
EOD;
		}
	}

}



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
                <td colspan=3></td><td align=left colspan=2>{$LANG['total']} {$LANG['tax']} {$LANG['included']}</td><td colspan=2 align=right>$preference[pref_currency_sign]{$invoice['total_tax']}</td>
        </tr>
	<tr><td><br></td>
	</tr>
        <tr>
                <td colspan=3></td><td align=left colspan=2><b>$preference[pref_inv_wording] {$LANG['amount']}</b></td><td colspan=2 align=right><u>$preference[pref_currency_sign]{$invoice['total']}</u></td>
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



	$url_pdf = "$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$invoice[id]&action=view&location=pdf&invoice_style={$invoice_type['inv_ty_description']}";
	$url_pdf_encoded = urlencode($url_pdf); 
	$url_for_pdf = "./pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&pdfname=$preference[pref_inv_wording]$invoice[id]&URL=$url_pdf_encoded";
	
	
echo <<<EOD
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


{$LANG['quick_view_of']} {$preference['pref_inv_wording']} {$invoiceID}
<br>



<!--Actions heading - start-->
{$LANG['actions']}: 
		<a href="index.php?module=invoices&view=templates/template&submit={$invoice['id']}&action=view&invoice_style={$invoice_type['inv_ty_description']}"> {$LANG['print_preview']}</a>
		 :: 
		<a href="index.php?module=invoices&view=details&submit={$invoice['id']}&action=view&invoice_style={$invoice_type['inv_ty_description']}"> {$LANG['edit']}</a>
		 ::
		 <a href='index.php?module=payments&view=process&submit={$invoice['id']}&op=pay_selected_invoice'> {$LANG['process_payment']} </a>
		 ::
		 <!-- EXPORT TO PDF -->
		<a href='{$url_for_pdf }'>{$LANG['export_pdf']}</a>
		::
		<a href="index.php?module=invoices&view=templates/template&submit={$invoice['id']}&action=view&invoice_style={$invoice_type['inv_ty_description']}&export={$spreadsheet}">{$LANG['export_as']} .{$spreadsheet}</a>
		::
		<a href="index.php?module=invoices&view=templates/template&submit={$invoice['id']}&action=view&invoice_style={$invoice_type['inv_ty_description']}&export={$word_processor}">{$LANG['export_as']} .{$word_processor} </a>
		:: <a href="index.php?module=invoices&view=email&stage=1&submit={$invoice['id']}">{$LANG['email']}</a>
<!--Actions heading - start-->
<hr></hr>
</form>
<!-- #PDF end -->

	<table align=center>
	<tr>
		<td class=account colspan=8>{$LANG['account_info']}</td><td width=5%></td><td class="columnleft" width=5%></td><td class="account" colspan=6><a href='index.php?module=customers&view=details&submit=$customer[id]&action=view'>{$LANG['customer_account']}</a></td>
	</tr>
	<tr>
		<td class=account>{$LANG['total']}:</td><td class=account>$preference[pref_currency_sign]{$invoice['total']}</td>
		<td class=account><a href='index.php?module=payments&view=manage&id=$invoice[id]'>{$LANG['paid']}:</a></td><td class=account>$preference[pref_currency_sign]{$invoice['paid_format']}</td>
		<td class=account>{$LANG['owing']}:</td><td class=account><u>$preference[pref_currency_sign]$invoice[owing]</u></td>
		<td class=account>{$LANG['age']}:</td><td class=account nowrap >$invoice_age <a href='docs.php?p=age&t=help' rel='gb_page_center[450, 450]'><img src="./images/common/help-small.png"></img></a></td>
		<td></td><td class="columnleft"></td>
		<td class="account">{$LANG['total']}:</td><td class=account>$preference[pref_currency_sign]$invoice[total_format]</td>
		<td class=account><a href='index.php?module=payments&view=manage&c_id=$customer[id]'>{$LANG['paid']}:</a></td><td class=account>$preference[pref_currency_sign]$invoice[paid_format]</td>
		<td class=account>{$LANG['owing']}:</td><td class=account><u>$preference[pref_currency_sign]$invoice[owing]</u></td>
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
		<td>$preference[pref_inv_wording] {$LANG['date']}:</td><td colspan=5>{$invoice['date']}</td>
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
		<td class='details_screen'>{$customFieldLabels['biller_cf1']}:</td><td class='details_screen' colspan=5>$biller[custom_field1]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels['biller_cf2']}:</td><td class='details_screen' colspan=5>$biller[custom_field2]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels['biller_cf3']}:</td><td class='details_screen' colspan=5>$biller[custom_field3]</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels['biller_cf4']}:</td><td class='details_screen' colspan=5>$biller[custom_field4]</td>
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
		<td class='details_screen'>{$LANG['street2']}:</td><td class='details_screen' colspan=5 align=left>$customer[street_address2]</td>
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
		<td class='details_screen'>{$customFieldLabels['customer_cf1']}:</td><td colspan=5 class='details_screen'>$customer[custom_field1]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customFieldLabels['customer_cf2']}:</td><td colspan=5 class='details_screen'>$customer[custom_field2]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customFieldLabels['customer_cf3']}:</td><td class='details_screen' colspan=5>$customer[custom_field3]</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customFieldLabels['customer_cf4']}:</td><td class='details_screen' colspan=5>$customer[custom_field4]</td>
	</tr>	


<hr></hr>
{$display_block_details}


        <tr>
                <td colspan=6><i>$preference[pref_inv_detail_line]</i></td>
        </tr>
	<tr>
		<td colspan=6>$preference[pref_inv_payment_method]</td>
        <tr>
                <td>$preference[pref_inv_payment_line1_name]</td><td colspan=5>$preference[pref_inv_payment_line1_value]</td>
        </tr>
        <tr>
                <td>$preference[pref_inv_payment_line2_name]</td><td colspan=5>$preference[pref_inv_payment_line2_value]</td>
        </tr>
        </table>
	<!-- addition close table tag to close invoice itemised/consulting if it has a note -->
	</table>

<hr></hr>
	<form>
		<input type=button value="{$LANG['cancel']}" onCLick="history.back()">
	</form>
EOD;

?>
