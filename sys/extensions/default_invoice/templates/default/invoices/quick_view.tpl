{*
/*
* Script: quick_view.tpl
* 	 Quick view of invoice template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ap.Muthu
*	 Modified for 'default_invoice by Marcel van Dorp. Version 20090208
*	 'Customer' section has an extra button to set the default invoice
*
* Last edited:
* 	 2008-01-03
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<div class="align_center">
	{$LANG.quick_view_of} {$preference.pref_inv_wording} {$invoice.id}
	<br /><br />


	<!--Actions heading - start-->
	<span class="welcome">
			<a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording} {$invoice.id}" href="index.php?module=invoices&view=template&id={$invoice.id}&action=view"> {$LANG.print_preview}</a>
			 :: 
			<a href="index.php?module=invoices&view=details&id={$invoice.id}&action=view"> {$LANG.edit}</a>
			 ::
			 <a href='index.php?module=payments&view=process&id={$invoice.id}&op=pay_selected_invoice'> {$LANG.process_payment} </a>
			 ::
			 <!-- EXPORT TO PDF -->
			<a href='index.php?module=invoices&view=template&id={$invoice.id}&action=view&location=pdf'>{$LANG.export_pdf}</a>
			::
			<a href="index.php?module=invoices&view=template&id={$invoice.id}&action=view&export={$spreadsheet}">{$LANG.export_as} .{$spreadsheet}</a>
			::
			<a href="index.php?module=invoices&view=template&id={$invoice.id}&action=view&export={$wordprocessor}">{$LANG.export_as} .{$wordprocessor} </a>
			::
			<a href="index.php?module=invoices&view=email&stage=1&invoice={$invoice.id}">{$LANG.email}</a>
			{if $defaults.delete == '1'} 
				:: 
				<a href="index.php?module=invoices&view=delete&stage=1&invoice={$invoice.id}">{$LANG.delete}</a>
			{/if}
	</span>
</div>
<!--Actions heading - start-->
<br />
<br />
<!-- #PDF end -->

	<table align="center">
{*
	<tr>
		<td colspan="6" class="align_center"><b>{$preference.pref_inv_heading}</b></td>
	</tr>
    <tr>
        <td colspan="6"><br /></td>
    </tr>
*}
	<!-- Invoice Summary section -->

	<tr class='details_screen'>
		<td class='details_screen'><b>{$preference.pref_inv_wording} {$LANG.summary}:</b></td>
		<td colspan="5" align="right" class='details_screen align_right'>
			<a href='#' class="show-summary" onclick="$('.summary').show();$('.show-summary').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" alt="" /></a>
			<a href='#' class="summary" onclick="$('.summary').hide();$('.show-summary').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" alt="" /></a>
		</td>
	</tr>
	<tr class='details_screen summary'>
		<td class='details_screen'>{$preference.pref_inv_wording} {$LANG.number_short}:</td>
		<td colspan="5" class='details_screen'>{$invoice.id}</td>
	</tr>
	<tr class='details_screen summary'>
		<td class='details_screen'>{$preference.pref_inv_wording} {$LANG.date}:</td>
		<td class='details_screen' colspan="5">{$invoice.date}</td>
	</tr>
	{$customField.1}
	{$customField.2}
	{$customField.3}
	{$customField.4}

	<tr>	
		<td><br /></td>
	</tr>
	<!-- Biller section -->

	<tr class='details_screen'>
		<td class='details_screen'><b>{$LANG.biller}:</b></td>
		<td class='details_screen' colspan="3">{$biller.name}</td>
		<td colspan="2" class='details_screen align_right'>
			<a href='#' class="show-biller" onclick="$('.biller').show();$('.show-biller').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" alt="" /></a>
			<a href='#' class="biller" onclick="$('.biller').hide();$('.show-biller').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" alt="" /></a></td>
	</tr>
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG.street}:</td>
		<td class='details_screen' colspan="5">{$biller.street_address}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG.street2}:</td>
		<td class='details_screen' colspan="5">{$biller.street_address2}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG.city}:</td>
		<td class='details_screen' colspan="3">{$biller.city}</td>
		<td class='details_screen'>{$LANG.phone_short}:</td>
		<td class='details_screen'>{$biller.phone}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG.state}, {$LANG.zip}:</td>
		<td class='details_screen' colspan="3">{$biller.state}, {$biller.zip_code}</td>
		<td class='details_screen'>{$LANG.mobile_short}:</td>
		<td class='details_screen'>{$biller.mobile_phone}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG.country}:</td>
		<td class='details_screen' colspan="3">{$biller.country}</td>
		<td class='details_screen'>{$LANG.fax}:</td>
		<td class='details_screen'>{$biller.fax}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$LANG.email}:</td>
		<td class='details_screen' colspan="5">{$biller.email}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels.biller_cf1}:</td>
		<td class='details_screen' colspan="5">{$biller.custom_field1}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels.biller_cf2}:</td>
		<td class='details_screen' colspan="5">{$biller.custom_field2}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels.biller_cf3}:</td>
		<td class='details_screen' colspan="5">{$biller.custom_field3}</td>
	</tr>	
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels.biller_cf4}:</td>
		<td class='details_screen' colspan="5">{$biller.custom_field4}</td>
	</tr>
	{*
		{showCustomFields categorieId="1" itemId=$biller.id }
	*}

	<tr>
		<td colspan="5"><br /></td>
	</tr>	
	
	<!-- Customer section -->
	<tr class='details_screen'>
		<td class='details_screen'><b>{$LANG.customer}:</b></td>
		<td class='details_screen' colspan="3">{$customer.name}</td>
		<td colspan="2" class='details_screen align_right'>
			<a href='#' class="show-customer" {literal} onclick="$('.customer').show(); $('.show-customer').hide(); {/literal}"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" alt=""/></a>
			<a href='#' class="customer" {literal} onclick="$('.customer').hide(); $('.show-customer').show(); {/literal}"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" alt="" /></a>
		</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG.attention_short}:</td>
		<td class='details_screen' colspan="5" align="left">{$customer.attention},</td>
	</tr>
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG.street}:</td>
		<td class='details_screen' colspan="5" align="left">{$customer.street_address}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG.street2}:</td>
		<td class='details_screen' colspan="5" align="left">{$customer.street_address2}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG.city}:</td>
		<td class='details_screen' colspan="3">{$customer.city}</td>
		<td class='details_screen'>Ph:</td>
		<td class='details_screen'>{$customer.phone}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG.state}, ZIP:</td>
		<td colspan="3" class='details_screen'>{$customer.state}, {$customer.zip_code}</td>
		<td class='details_screen'>{$LANG.fax}:</td><td class='details_screen'>{$customer.fax}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG.country}:</td>
		<td class='details_screen' colspan="3">{$customer.country}</td>
		<td class='details_screen'>Mobile:</td>
		<td class='details_screen'>{$customer.mobile_phone}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$LANG.email}:</td>
		<td class='details_screen'colspan="5">{$customer.email}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customFieldLabels.customer_cf1}:</td>
		<td colspan="5" class='details_screen'>{$customer.custom_field1}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customFieldLabels.customer_cf2}:</td>
		<td colspan="5" class='details_screen'>{$customer.custom_field2}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customFieldLabels.customer_cf3}:</td>
		<td class='details_screen' colspan="5">{$customer.custom_field3}</td>
	</tr>	
	<tr class='details_screen customer'>
		<td class='details_screen'>{$customFieldLabels.customer_cf4}:</td>
		<td class='details_screen' colspan=4>{$customer.custom_field4}</td>
		<td class='details_screen align_right'>
		<a href="?module=invoices&view=usedefault&action=update_template&id={$invoice.id}&customer_id={$customer.id}"><img src="./images/flexigrid/load.png" title='{$LANG.invoice} {$invoice.id} {$LANG.as_template} {$LANG.for} {$customer.name}' alt="" /></a>
		</td>

	</tr>	
			{*
				{showCustomFields categorieId="2" itemId=$customer.id }
			*}

{if $invoice.type_id == 1 }

	        <tr>
	                <td colspan="6"><br /></td>
        	</tr>
	        <tr>
        	        <td colspan="6"><b>{$LANG.description}</b></td>
	        </tr>
	        <tr>
	                <td colspan="6">{$invoiceItems.0.description|unescape}</td>
        	</tr>
	        <tr>
        	        <td colspan="6"><br /></td>
	        </tr>
	        <tr>
	                <td></td>
					<td></td>
					<td></td>
					<td style="text-align:right"><b>{$LANG.gross_total}</b></td>
					<td style="text-align:right"><b>{$LANG.tax}</b></td>
					<td style="text-align:right"><b>{$LANG.total_uppercase}</b></td>
        	</tr>
	        <tr>
        	        <td></td>
					<td></td>
					<td></td>
					<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItems.0.gross_total|number_format:2}</td>
					<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItems.0.tax_amount|number_format:2}</td>
					<td style="text-align:right"><u>{$preference.pref_currency_sign}{$invoiceItems.0.total|number_format:2}</u></td>
	        </tr>

        	<tr>
                	<td colspan="6"><br /><br /></td>
	        </tr>
        	<tr>
                	<td colspan="6"><b>{$preference.pref_inv_detail_heading}</b></td>
	        </tr>


{/if}

{if $invoice.type_id == 2 || $invoice.type_id == 3  || $invoice.type_id == 4}

        <tr>
                <td colspan="6"><br /></td>
        </tr>
		<tr>
		<td colspan="6">
		<table width="100%"> 
	
	{if $invoice.type_id == 2 }

            <tr>
                    <td colspan="6" class="details_screen align_right">
                    <a href='#' class="show-itemised" onclick="$('.itemised').show();$('.show-itemised').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}"/></a>
                    <a href='#' class="itemised" onclick="$('.itemised').hide();$('.show-itemised').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}"/></a>
                    </td>
            </tr>
			<tr>
        		    <td><b>{$LANG.quantity_short}</b></td>
					<td colspan="2"><b>{$LANG.item}</b></td>
					<td style="text-align:right"><b>{$LANG.Unit_Cost}</b></td>
					<td style="text-align:right"><b>{$LANG.Price}</b></td>
		    </tr>
		    
	{/if}


    {if $invoice.type_id == 3 }

			<tr>
					<td colspan="6" class="details_screen align_right">
					<a href='#' class="show-consulting" onclick="$('.consulting').show();$('.show-consulting').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}"/></a>
					<a href='#' class="consulting" onclick="$('.consulting').hide();$('.show-consulting').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}"/></a>
					</td>
        	</tr>
			<tr>
               	 	<td><b>{$LANG.quantity_short}</b></td>
					<td colspan="2"><b>{$LANG.item}</b></td>
					<td style="text-align:right"><b>{$LANG.Unit_Cost}</b></td>
					<td style="text-align:right"><b>{$LANG.Price}</b></td>
	        </tr>
    {/if}


{foreach from=$invoiceItems item=invoiceItem }
			
		{if $invoice.type_id == 2 }
	
			<tr>
	                <td>{$invoiceItem.quantity|siLocal_number_trim}</td>
					<td colspan="2">{$invoiceItem.product.description}</td>
					<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
					<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|siLocal_number}</td>
	        </tr>
	        
	        {if $invoiceItem.description != null}
				<tr class='show-itemised' >
					<td>
					</td>	
					<td colspan="5" class=''>
						{$LANG.description}: {$invoiceItem.description|truncate:15:"...":true}
					</td>
				</tr>
				<tr class='itemised' >	
					<td colspan="6" class='details_screen'>
						{$LANG.description}: {$invoiceItem.description}</td>
				</tr>
			{/if}
	        

			<tr class='itemised'>       
				<td colspan="6">
					<table width=100%>
						<tr>
							<td width="50%" class='details_screen'>{$customFieldLabels.product_cf1}: {$invoiceItem.product.custom_field1}</td>
							<td width="50%" class='details_screen'>{$customFieldLabels.product_cf2}: {$invoiceItem.product.custom_field2}</td>
						</tr>
						<tr>       
							<td width="50%" class='details_screen'>{$customFieldLabels.product_cf3}: {$invoiceItem.product.custom_field3}</td>
							<td width="50%" class='details_screen'>{$customFieldLabels.product_cf4}: {$invoiceItem.product.custom_field4}</td>
						</tr>
					</table>
				</td>
			</tr>
			 {*TODO: CustomField is normaly stored for a product. Here it needs to be added to the invoices Item
			 	-> categorie 5 *}
			{*
				{showCustomFields categorieId="3" itemId=$invoiceItem.productId }
			*}

	{/if}	
	

	{if $invoice.type_id == 3 }

			<tr>
	            <td>{$invoiceItem.quantity|number_format:2}</td>
				<td colspan="2">{$invoiceItem.product.description}</td>
				<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
				<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|siLocal_number}</td>

			</tr>
			<tr  class='consulting' >	
				<td colspan="6">
					<table width="100%">
						<tr>
                            <td width="50%" class='details_screen'>{$customFieldLabels.product_cf1}: {$invoiceItem.product.custom_field1}</td>
							<td width=50% class='details_screen'>{$customFieldLabels.product_cf2}: {$invoiceItem.product.custom_field2}</td>
						</tr>
						<tr>       
                            <td width="50%" class='details_screen'>{$customFieldLabels.product_cf3}: {$invoiceItem.product.custom_field3}</td>
							<td width="50%" class='details_screen'>{$customFieldLabels.product_cf4}: {$invoiceItem.product.custom_field4}</td>
						</tr>
					</table>
				</td>
	<!--		<td></td><td colspan="6" class='details_screen consulting'>{$prod_custom_field_label1}: {$product.custom_field1}, {$prod_custom_field_label2}: {$product.custom_field2}, {$prod_custom_field_label3}: {$product.custom_field3}, {$prod_custom_field_label4}: {$product.custom_field4}</td> -->
			</tr>
		 

	{/if}

{/foreach}

<!-- we are still in the itemised or consulting loop -->
		</table>
		</td>
		</tr>

		{if ($invoice.note != null) }
		<tr>
				<td colspan="6">&nbsp;</td>
		</tr>
		<tr class="details_screen">
			<td colspan="5"><b>{$LANG.notes}:</b></td>
				{if ($invoice.note|count_characters:true > 25)}
					<td class="details_screen align_right">
						<a href='#' class="show-notes" onclick="$('.notes').show();$('.show-notes').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" /></a>
						<a href='#' class="notes" onclick="$('.notes').hide();$('.show-notes').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" /></a>
					</td>
				{/if}						
		</tr>
			<!-- if hide detail click - the stripped note will be displayed -->
		<tr class='show-notes details_screen'>
				<td colspan="6">{$invoice.note|truncate:25:"...":true}</td>
		</tr>
			<!-- if show detail click - the full note will be displayed -->
		<tr class='notes details_screen'>
				<td colspan="6">{$invoice.note|unescape}</td>
		</tr>
		{/if}

	<tr>
		<td colspan="6"><br /></td>
	</tr>	


    {section name=line start=0 loop=$invoice.tax_grouped step=1}
    
    	{if ($invoice.tax_grouped[line].tax_amount == "0") } {php}break;{/php} {/if}
    	
    	<tr class='details_screen'>
	        <td colspan="3"></td>
			<td colspan="2" class="align_right">{$invoice.tax_grouped[line].tax_name}</td>
			<td colspan="1" class="align_right">{$preference.pref_currency_sign}{$invoice.tax_grouped[line].tax_amount|siLocal_number}</td>
	    </tr>
	    
	{/section}
	
	<tr class='details_screen'>
        <td colspan="3"></td>
		<td colspan="2" class="align_right">{$LANG.tax_total}</td>
		<td colspan="1" class="align_right"><u>{$preference.pref_currency_sign}{$invoice.total_tax|siLocal_number}</u></td>
    </tr>
	<tr>
		<td colspan="6"><br /></td>
	</tr>
    <tr class='details_screen'>
        <td colspan="3"></td>
		<td colspan="2" class="align_right"><b>{$preference.pref_inv_wording} {$LANG.amount}</b></td>
		<td colspan="2" class="align_right"><span class="double_underline">{$preference.pref_currency_sign}{$invoice.total|siLocal_number}</span></td>
    </tr>
{*
	<tr>
		<td colspan="6"><br /><br /></td>
	</tr>	

	<tr>
		<td colspan="6"><b>{$preference.pref_inv_detail_heading}</b></td>
	</tr>
*}
{/if}
	{*
		{showCustomFields categorieId="4" itemId=$invoice.id }
	*}
{*
    <tr>
        <td colspan="6"><i>{$preference.pref_inv_detail_line}</i></td>
    </tr>
	<tr>
		<td colspan="6">{$preference.pref_inv_payment_method}</td>
	</tr>
    <tr>
        <td>{$preference.pref_inv_payment_line1_name}</td>
		<td colspan="5">{$preference.pref_inv_payment_line1_value}</td>
    </tr>
    <tr>
        <td>{$preference.pref_inv_payment_line2_name}</td>
		<td colspan="5">{$preference.pref_inv_payment_line2_value}</td>
    </tr>
*}
</table>

<br /><br />
	<table align="center">
	<tr class='details_screen'>
		<td class='details_screen' colspan="16">
		{$LANG.financial_status}
		</td>
	</tr>
	<tr class="account">
		<td class="account" colspan="8">{$preference.pref_inv_wording} {$invoice.id}</td>
		<td width=5%></td>
		<td class="columnleft" width="5%"></td>
		<td class="account" colspan="6"><a href='index.php?module=customers&view=details&id={$customer.id}&action=view'>{$LANG.customer_account}</a></td>
	</tr>
	<tr>
		<td class="account">{$LANG.total}:</td>
		<td class="account">{$preference.pref_currency_sign}{$invoice.total|number_format:2}</td>
		<td class="account"><a href='index.php?module=payments&view=manage&id={$invoice.id}'>{$LANG.paid}:</a></td>
		<td class="account">{$preference.pref_currency_sign}{$invoice.paid|number_format:2}</td>
		<td class="account">{$LANG.owing}:</td>
		<td class="account"><u>{$preference.pref_currency_sign}{$invoice.owing|number_format:2}</u></td>
		<td class="account">{$LANG.age}:</td>
		<td class="account" nowrap>{$invoice_age} 
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_age" title="{$LANG.age}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td></td>
		<td class="columnleft"></td>
		<td class="account">{$LANG.total}:</td>
		<td class="account">{$preference.pref_currency_sign}{$customerAccount.total|number_format:2}</td>
		<td class="account"><a href='index.php?module=payments&view=manage&c_id={$customer.id}'>{$LANG.paid}:</a></td>
		<td class="account">{$preference.pref_currency_sign}{$customerAccount.paid|number_format:2} </td>
		<td class="account">{$LANG.owing}:</td>
		<td class="account"><u>{$preference.pref_currency_sign}{$customerAccount.owing|number_format:2}</u></td>
	</tr>
	</table>
<br />
