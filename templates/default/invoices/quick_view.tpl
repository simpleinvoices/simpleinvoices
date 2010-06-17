{*
/*
* Script: quick_view.tpl
* 	 Quick view of invoice template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ap.Muthu
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
	<br />


	<!--Actions heading - start-->
	<span class="welcome">
			<a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}" href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id|urlencode}&amp;format=print"><img src='images/common/printer.png' class='action' />&nbsp;{$LANG.print_preview}</a>
			 &nbsp;&nbsp; 
			<a title="{$LANG.edit} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}" href="index.php?module=invoices&amp;view=details&amp;id={$invoice.id|urlencode}&amp;action=view"><img src='images/common/edit.png' class='action' />&nbsp;{$LANG.edit}</a>
			 &nbsp;&nbsp; 
			 <a title="{$LANG.process_payment_for} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}" href="index.php?module=payments&amp;view=process&amp;id={$invoice.id|urlencode}&amp;op=pay_selected_invoice"><img src='images/common/money_dollar.png' class='action' />&nbsp;{$LANG.process_payment} </a>
             {if $eway_pre_check == 'true'}
			 &nbsp;&nbsp; 
			 <a title="{$LANG.process_payment_for} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}" href="index.php?module=payments&amp;view=eway&amp;id={$invoice.id|urlencode}"><img src='images/common/money_dollar.png' class='action' />&nbsp;{$LANG.process_payment_via_eway} </a>
             {/if}
			 &nbsp;&nbsp; 
			 <!-- EXPORT TO PDF -->
			<a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_pdf_tooltip}" href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=pdf"><img src='images/common/page_white_acrobat.png' class='action' />&nbsp;{$LANG.export_pdf}</a>
			 &nbsp;&nbsp; 
			<a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet|htmlsafe} {$LANG.format_tooltip}" href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$spreadsheet|urlencode}"><img src='images/common/page_white_excel.png' class='action' />&nbsp;{$LANG.export_as} .{$spreadsheet|htmlsafe}</a>
			 &nbsp;&nbsp; 
			<a title="{$LANG.export_tooltip} {$preference.pref_inv_wording} {$invoice.id|htmlsafe} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor|htmlsafe} {$LANG.format_tooltip}" href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$wordprocessor|urlencode}"><img src='images/common/page_white_word.png' class='action' />&nbsp;{$LANG.export_as} .{$wordprocessor|htmlsafe} </a>
			 &nbsp;&nbsp; 
			<a title="{$LANG.email} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}" href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice.id|urlencode}"><img src='images/common/mail-message-new.png' class='action' />&nbsp;{$LANG.email}</a>
			{if $defaults.delete == '1'} 
			 &nbsp;&nbsp; 
				<a title="{$LANG.delete} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}" href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={$invoice.id|urlencode}"><img src='images/common/delete.png' class='action' />&nbsp;{$LANG.delete}</a>
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
		<td colspan="6" class="align_center"><b>{$preference.pref_inv_heading|htmlsafe}</b></td>
	</tr>
    <tr>
        <td colspan="6"><br /></td>
    </tr>
*}
	<!-- Invoice Summary section -->

	<tr class="details_screen">
		<td class="details_screen"><b>{$preference.pref_inv_wording|htmlsafe} {$LANG.summary}:</b></td><td colspan="5" align="right" class="details_screen align_right"><a href='#' class="show-summary" onclick="$('.summary').show();$('.show-summary').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" /></a><a href='#' class="summary" onclick="$('.summary').hide();$('.show-summary').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" /></a> </td>
	</tr>
	<tr class="details_screen summary">
		<td class="details_screen">{$preference.pref_inv_wording|htmlsafe} {$LANG.number_short}:</td><td colspan="5" class="details_screen">{$invoice.index_id|htmlsafe}</td>
	</tr>
	<tr class="details_screen summary">
		<td class="details_screen">{$preference.pref_inv_wording|htmlsafe} {$LANG.date}:</td>
		<td class="details_screen" colspan="5">{$invoice.date|htmlsafe}</td>
	</tr>
	{$customField.1}
	{$customField.2}
	{$customField.3}
	{$customField.4}

	<tr>	
		<td><br /></td>
	</tr>
	<!-- Biller section -->

	<tr class="details_screen">
		<td class="details_screen"><b>{$LANG.biller}:</b></td>
		<td class="details_screen" colspan="3">{$biller.name|htmlsafe}</td>
		<td colspan="2" class="details_screen align_right"><a href='#' class="show-biller" onclick="$('.biller').show();$('.show-biller').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" /></a><a href='#' class="biller" onclick="$('.biller').hide();$('.show-biller').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" /></a></td>
	</tr>
	<tr class="details_screen biller">
		<td class="details_screen">{$LANG.street}:</td>
		<td class="details_screen" colspan="5">{$biller.street_address|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$LANG.street2}:</td>
		<td class="details_screen" colspan="5">{$biller.street_address2|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$LANG.city}:</td>
		<td class="details_screen" colspan="3">{$biller.city|htmlsafe}</td>
		<td class="details_screen">{$LANG.phone_short}:</td>
		<td class="details_screen">{$biller.phone|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$LANG.state}, {$LANG.zip}:</td>
		<td class="details_screen" colspan="3">{$biller.state|htmlsafe}, {$biller.zip_cod|htmlsafe}</td>
		<td class="details_screen">{$LANG.mobile_short}:</td>
		<td class="details_screen">{$biller.mobile_phone|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$LANG.country}:</td>
		<td class="details_screen" colspan="3">{$biller.country|htmlsafe}</td>
		<td class="details_screen">{$LANG.fax}:</td>
		<td class="details_screen">{$biller.fax|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$LANG.email}:</td>
		<td class="details_screen" colspan="5">{$biller.email|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$customFieldLabels.biller_cf1|htmlsafe}:</td>
		<td class="details_screen" colspan="5">{$biller.custom_field1|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$customFieldLabels.biller_cf2|htmlsafe}:</td>
		<td class="details_screen" colspan="5">{$biller.custom_field2|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$customFieldLabels.biller_cf3|htmlsafe}:</td>
		<td class="details_screen" colspan="5">{$biller.custom_field3|htmlsafe}</td>
	</tr>	
	<tr class="details_screen biller">
		<td class="details_screen">{$customFieldLabels.biller_cf4|htmlsafe}:</td>
		<td class="details_screen" colspan="5">{$biller.custom_field4|htmlsafe}</td>
	</tr>
	{*
		{showCustomFields categorieId="1" itemId=$biller.id }
	*}

	<tr>
		<td colspan="5"><br /></td>
	</tr>	
	
	<!-- Customer section -->
	<tr class="details_screen">
		<td class="details_screen"><b>{$LANG.customer}:</b></td>
		<td class="details_screen" colspan="3">{$customer.name|htmlsafe}</td>
		<td colspan="2" class="details_screen align_right"><a href='#' class="show-customer" {literal} onclick="$('.customer').show(); $('.show-customer').hide(); {/literal}"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}"/></a> <a href='#' class="customer" {literal} onclick="$('.customer').hide(); $('.show-customer').show(); {/literal}"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" /></a></td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$LANG.attention_short}:</td>
		<td class="details_screen" colspan="5" align="left">{$customer.attention|htmlsafe},</td>
	</tr>
	<tr class="details_screen customer">
		<td class="details_screen">{$LANG.street}:</td>
		<td class="details_screen" colspan="5" align="left">{$customer.street_address|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$LANG.street2}:</td>
		<td class="details_screen" colspan="5" align="left">{$customer.street_address2|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$LANG.city}:</td>
		<td class="details_screen" colspan="3">{$customer.city|htmlsafe}</td>
		<td class="details_screen">Ph:</td>
		<td class="details_screen">{$customer.phone|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$LANG.state}, ZIP:</td>
		<td colspan="3" class="details_screen">{$customer.state|htmlsafe}, {$customer.zip_code|htmlsafe}</td>
		<td class="details_screen">{$LANG.fax}:</td>
		<td class="details_screen">{$customer.fax|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$LANG.country}:</td>
		<td class="details_screen" colspan="3">{$customer.country|htmlsafe}</td>
		<td class="details_screen">Mobile:</td>
		<td class="details_screen">{$customer.mobile_phone|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$LANG.email}:</td>
		<td class="details_screen"colspan="5">{$customer.email|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$customFieldLabels.customer_cf1}:</td>
		<td colspan="5" class="details_screen">{$customer.custom_field1|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$customFieldLabels.customer_cf2}:</td>
		<td colspan="5" class="details_screen">{$customer.custom_field2|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$customFieldLabels.customer_cf3}:</td>
		<td class="details_screen" colspan="5">{$customer.custom_field3|htmlsafe}</td>
	</tr>	
	<tr class="details_screen customer">
		<td class="details_screen">{$customFieldLabels.customer_cf4}:</td>
		<td class="details_screen" colspan="5">{$customer.custom_field4|htmlsafe}</td>
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
	                <td colspan="6">{$invoiceItems.0.description|outhtml}</td>
        	</tr>
{/if}

{if $invoice.type_id == 2 || $invoice.type_id == 3}

        <tr>
                <td colspan="6"><br /></td>
        </tr>
		<tr>
		<td colspan="6">
		<table width="100%"> 
	
	{if $invoice.type_id == 2 }

            <tr>
                    <td colspan="6" class="details_screen align_right"><a href='#' class="show-itemised" onclick="$('.itemised').show();$('.show-itemised').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}"/></a><a href='#' class="itemised" onclick="$('.itemised').hide();$('.show-itemised').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}"/></a></td>
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
					<td colspan="6" class="details_screen align_right"><a href='#' class="show-consulting" onclick="$('.consulting').show();$('.show-consulting').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}"/></a><a href='#' class="consulting" onclick="$('.consulting').hide();$('.show-consulting').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}"/></a></td>
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
					<td colspan="2">{$invoiceItem.product.description|htmlsafe}</td>
					<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
					<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|siLocal_number}</td>
	        </tr>
	        
	        {if $invoiceItem.description != null}
				<tr class="show-itemised" >
					<td>
					</td>	
					<td colspan="5" class="">
						{$LANG.description}: {$invoiceItem.description|truncate:15:"...":true|htmlsafe}
					</td>
				</tr>
				<tr class="itemised" >	
					<td colspan="6" class="details_screen">
						{$LANG.description}: {$invoiceItem.description|htmlsafe}</td>
				</tr>
			{/if}
	        

			<tr class="itemised">       
				<td colspan="6">
					<table width=100%>
						<tr>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf1|htmlsafe}: {$invoiceItem.product.custom_field1|htmlsafe}</td>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf2|htmlsafe}: {$invoiceItem.product.custom_field2|htmlsafe}</td>
						</tr>
						<tr>       
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf3|htmlsafe}: {$invoiceItem.product.custom_field3|htmlsafe}</td>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf4|htmlsafe}: {$invoiceItem.product.custom_field4|htmlsafe}</td>
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
	            <td>{$invoiceItem.quantity|siLocal_number}</td>
				<td colspan="2">{$invoiceItem.product.description|htmlsafe}</td>
				<td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.unit_price|siLocal_number}</td>
				<td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.gross_total|siLocal_number}</td>

			</tr>
			<tr  class="consulting" >	
				<td colspan="6">
					<table width="100%">
						<tr>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf1|htmlsafe}: {$invoiceItem.product.custom_field1|htmlsafe}</td>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf2|htmlsafe}: {$invoiceItem.product.custom_field2|htmlsafe}</td>
						</tr>
						<tr>       
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf3|htmlsafe}: {$invoiceItem.product.custom_field3|htmlsafe}</td>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf4|htmlsafe}: {$invoiceItem.product.custom_field4|htmlsafe}</td>
						</tr>
					</table>
				</td>
	<!--		<td></td><td colspan="6" class="details_screen consulting">{$prod_custom_field_label1|htmlsafe}: {$product.custom_field1|htmlsafe}, {$prod_custom_field_label2|htmlsafe}: {$product.custom_field2|htmlsafe}, {$prod_custom_field_label3|htmlsafe}: {$product.custom_field3|htmlsafe}, {$prod_custom_field_label4|htmlsafe}: {$product.custom_field4|htmlsafe}</td> -->
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
					<td class="details_screen align_right"><a href='#' class="show-notes" onclick="$('.notes').show();$('.show-notes').hide();"><img src="./images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" /></a><a href='#' class="notes" onclick="$('.notes').hide();$('.show-notes').show();"><img src="./images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" /></a></td>
				{/if}						
		</tr>
			<!-- if hide detail click - the stripped note will be displayed -->
		<tr class="show-notes details_screen">
				<td colspan="6">{$invoice.note|truncate:25:"...":true|outhtml}</td>
		</tr>
			<!-- if show detail click - the full note will be displayed -->
		<tr class="notes details_screen">
				<td colspan="6">{$invoice.note|outhtml}</td>
		</tr>
		{/if}
{* end itemised invoice *}
{/if} 
		<tr>
				<td colspan="6">&nbsp;</td>
		</tr>
    {* tax section - start *}
	{if $invoice_number_of_taxes > 0}
	<tr class="details_screen">
        <td colspan="3"></td>
		<td colspan="2" class="align_right">{$LANG.sub_total}&nbsp;</td>
		<td colspan="1" class="align_right">{if $invoice_number_of_taxes > 1}<u>{/if}{$preference.pref_currency_sign|htmlsafe}{$invoice.gross|siLocal_number}{if $invoice_number_of_taxes > 1}</u>{/if}</td>
    </tr>
    {/if}
	{if $invoice_number_of_taxes > 1 }
	        <tr>
        	        <td colspan="6"><br /></td>
	        </tr>
    {/if}
    {section name=line start=0 loop=$invoice.tax_grouped step=1}
    	{if ($invoice.tax_grouped[line].tax_amount != "0") }
    	
    	<tr class="details_screen">
	        <td colspan="3"></td>
			<td colspan="2" class="align_right">{$invoice.tax_grouped[line].tax_name|htmlsafe}&nbsp;</td>
			<td colspan="1" class="align_right">{$preference.pref_currency_sign|htmlsafe}{$invoice.tax_grouped[line].tax_amount|siLocal_number}</td>
	    </tr>
	    {/if}
	    
	{/section}
	{if $invoice_number_of_taxes > 1}
	<tr class="details_screen">
        <td colspan="3"></td>
		<td colspan="2" class="align_right">{$LANG.tax_total}&nbsp;</td>
		<td colspan="1" class="align_right"><u>{$preference.pref_currency_sign|htmlsafe}{$invoice.total_tax|siLocal_number}</u></td>
    </tr>
    {/if}
	{if $invoice_number_of_taxes > 1}
	<tr>
		<td colspan="6"><br /></td>
	</tr>
    {/if}
    <tr class="details_screen">
        <td colspan="3"></td>
		<td colspan="2" class="align_right"><b>{$preference.pref_inv_wording|htmlsafe} {$LANG.amount}&nbsp;</b></td>
		<td colspan="1" class="align_right"><span class="double_underline">{$preference.pref_currency_sign|htmlsafe}{$invoice.total|siLocal_number}</span></td>
    </tr>
    {* tax section - end *}
</table>

<br /><br />
	<table align="center">
	<tr class="details_screen">
		<td class="details_screen" colspan="16">
		{$LANG.financial_status}
		</td>
	</tr>
	<tr class="account">
		<td class="account" colspan="8">{$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}</td>
		<td width=5%></td>
		<td class="columnleft" width="5%"></td>
		<td class="account" colspan="6"><a href="index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=view">{$LANG.customer_account}</a></td>
	</tr>
	<tr>
		<td class="account">{$LANG.total}:</td>
		<td class="account">{$preference.pref_currency_sign}{$invoice.total|siLocal_number}</td>
		<td class="account"><a href="index.php?module=payments&amp;view=manage&amp;id={$invoice.id|urlencode}">{$LANG.paid}:</a></td>
		<td class="account">{$preference.pref_currency_sign|htmlsafe}{$invoice.paid|siLocal_number}</td>
		<td class="account">{$LANG.owing}:</td>
		<td class="account"><u>{$preference.pref_currency_sign|htmlsafe}{$invoice.owing|siLocal_number}</u></td>
		<td class="account">{$LANG.age}:</td>
		<td class="account" nowrap>{$invoice_age|htmlsafe} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_age" title="{$LANG.age}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td></td>
		<td class="columnleft"></td>
		<td class="account">{$LANG.total}:</td>
		<td class="account">{$preference.pref_currency_sign|htmlsafe}{$customerAccount.total|siLocal_number}</td>
		<td class="account"><a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlencode}">{$LANG.paid}:</a></td>
		<td class="account">{$preference.pref_currency_sign|htmlsafe}{$customerAccount.paid|siLocal_number} </td>
		<td class="account">{$LANG.owing}:</td>
		<td class="account"><u>{$preference.pref_currency_sign|htmlsafe}{$customerAccount.owing|siLocal_number}</u></td>
	</tr>
	</table>
<br />
