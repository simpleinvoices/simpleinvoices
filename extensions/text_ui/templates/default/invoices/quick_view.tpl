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

{literal}
	
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
{/literal}


{$LANG.quick_view_of} {$preference.pref_inv_wording} {$invoice.id}
<br />



<!--Actions heading - start-->
{$LANG.actions}: 
		<a href="index.php?module=invoices&amp;view=details&amp;invoice={$invoice.id}&amp;action=view"> {$LANG.edit}</a>
		 ::
		 <a href="index.php?module=payments&amp;view=process&amp;invoice={$invoice.id}&amp;op=pay_selected_invoice"> {$LANG.process_payment} </a>
		 ::
		<a href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;invoice={$invoice.id}">{$LANG.email}</a>
		{if $defaults.delete == '1'} 
			:: 
			<a href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;invoice={$invoice.id}">{$LANG.delete}</a>
		{/if}

<!--Actions heading - start-->
<hr />

<!-- #PDF end -->
	<!-- Invoice Summary section -->

		<b>{$preference.pref_inv_wording} {$LANG.summary}:</b><br />
		{$preference.pref_inv_wording} {$LANG.number_short}: {$invoice.id} :: {$preference.pref_inv_wording} {$LANG.date}: {$invoice.date}
	{$customField.1}
	{$customField.2}
	{$customField.3}
	{$customField.4}
<br />
<br />
	<!-- Biller section -->

		<b>{$LANG.biller}:</b> {$biller.name}<br />
		<b>{$LANG.customer}:</b> {$customer.name}<br />

	<table width="100%">
{if $invoice.type_id == 1 }

	        <tr>
        	        <td colspan="6"><b>{$LANG.description}</b></td>
	        </tr>
	        <tr>
	                <td colspan="6">{$invoiceItems.0.description}</td>
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
		<td colspan="6">
		<table width="100%"> 
	
	{if $invoice.type_id == 2 || $invoice.type_id == 4}

            <tr>
                    <td colspan="6" class="details_screen align_right"><a href='#' class="show-itemised" onclick="javascript: $('.itemised').show();$('.show-itemised').hide();">{$LANG.show_details}</a><a href='#' class="itemised" onclick="javascript: $('.itemised').hide();$('.show-itemised').show();">{$LANG.hide_details}</a></td>
            </tr>
			<tr>
        		    <td><b>{$LANG.quantity_short}</b></td>
					<td colspan="3"><b>{$LANG.description}</b></td>
			</tr>
			<tr>
					<td><i>{$LANG.unit_price}</i></td>
					<td><i>{$LANG.gross_total}</i></td>
					<td style="text-align:right"><i>{$LANG.tax}</i></td>
					<td style="text-align:right"><i>{$LANG.total_uppercase}</i></td>
		    </tr>
	{/if}


    {if $invoice.type_id == 3 }

			<tr>
					<td colspan="6" class="details_screen align_right"><a href='#' class="show-consulting" onclick="javascript: $('.consulting').show();$('.show-consulting').hide();">{$LANG.show_details}</a><a href='#' class="consulting" onclick="javascript: $('.consulting').hide();$('.show-consulting').show();">{$LANG.hide_details}</a></td>
        	</tr>
			<tr>
               	 	<td><b>{$LANG.quantity_short}</b></td>
					<td colspan="3"><b>{$LANG.item}</b></td>
			</tr>
			<tr>
					<td ><i>{$LANG.unit_price}</i></td>
					<td ><i>{$LANG.gross_total}</i></td>
					<td style="text-align:right"><i>{$LANG.tax}</i></td>
					<td style="text-align:right"><i>{$LANG.total_uppercase}</i></td>
	        </tr>
    {/if}


{foreach from=$invoiceItems item=invoiceItem }
			
		{if $invoice.type_id == 2 || $invoice.type_id == 4}
	
			<tr>
	                <td>{$invoiceItem.quantity|number_format:2}</td>
					<td colspan="3">{$invoiceItem.product.description} {if $invoiceItem.attr1.display != "" } ::{/if} {$invoiceItem.attr1.display}  {$invoiceItem.attr2.display} {$invoiceItem.attr3.display}</td>
			</tr>
			<tr>
					<td >{$preference.pref_currency_sign}{$invoiceItem.unit_price|number_format:2}</td>
					<td >{$preference.pref_currency_sign}{$invoiceItem.gross_total|number_format:2}</td>
					<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.tax_amount|number_format:2}</td>
					<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.total|number_format:2}</td>
	        </tr>
	        

			<tr class='itemised'>       
				<td colspan="6">
					<table width="100%">
						<tr>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf1}: {$invoiceItem.product.custom_field1}</td>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf2}: {$invoiceItem.product.custom_field2}</td>
						</tr>
						<tr>       
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf3}: {$invoiceItem.product.custom_field3}</td>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf4}: {$invoiceItem.product.custom_field4}</td>
						</tr>
					</table>
				</td>
			</tr>

	{/if}	
	

	{if $invoice.type_id == 3 }

			<tr>
	            <td>{$invoiceItem.quantity|number_format:2}</td>
				<td>{$invoiceItem.product.description}</td>
				<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|number_format:2}</td>
				<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|number_format:2}</td>
				<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.tax_amount|number_format:2}</td>
				<td style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.total|number_format:2}</td>
			</tr>
			<tr  class='consulting' >	
				<td colspan="6">
					<table width="100%">
						<tr>
                            <td width="50%" class="details_screen">{$customFieldLabels.product_cf1}: {$invoiceItem.product.custom_field1}</td>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf2}: {$invoiceItem.product.custom_field2}</td>
						</tr>
						<tr>       
                            <td width="50%" class="details_screen">{$customFieldLabels.product_cf3}: {$invoiceItem.product.custom_field3}</td>
							<td width="50%" class="details_screen">{$customFieldLabels.product_cf4}: {$invoiceItem.product.custom_field4}</td>
						</tr>
					</table>
				</td>
	<!--		<td></td><td colspan="6" class='details_screen consulting'>{$prod_custom_field_label1}: {$product.custom_field1}, {$prod_custom_field_label2}: {$product.custom_field2}, {$prod_custom_field_label3}: {$product.custom_field3}, {$prod_custom_field_label4}: {$product.custom_field4}</td> -->
			</tr>
		 
		{if $invoiceItem.description != null}
			<tr class='show-consulting' >	
				<td colspan="6" class='details_screen consulting'>{$invoiceItem.description|truncate:"..."}</td>
			</tr>
			<tr class='consulting' >	
				<td colspan="6" class='details_screen consulting'>{$LANG.description}:<br />{$invoiceItem.description}</td>
			</tr>
		{/if}
	{/if}

{/foreach}

<!-- we are still in the itemised or consulting loop -->
		</table>
		</table>

		{if ($invoice.note != null) }
		<b>{$LANG.notes}:</b>
		<table>
		<td>
				<td class="details_screen align_right"><a href='#' class="show-notes" onclick="javascript: $('.notes').show();$('.show-notes').hide();">{$LANG.show_details}</a><a href='#' class="notes" onclick="javascript: $('.notes').hide();$('.show-notes').show();">{$LANG.hide_details}</a></td>
		</tr>
			<!-- if hide detail click - the stripped note will be displayed -->
		<tr class='show-notes details_screen'>
				<td colspan="6">{$invoice.note|truncate:"..."}</td>
		</tr>
			<!-- if show detail click - the full note will be displayed -->
		<tr class='notes details_screen'>
				<td colspan="6">{$invoice.note}</td>
		</tr>
		</table>
		{/if}
<br />
		{$LANG.total} {$LANG.tax} {$LANG.included}: {$preference.pref_currency_sign}{$invoice.total_tax|number_format:2}<br />
		<b>{$preference.pref_inv_wording} {$LANG.amount}</b>: <u>{$preference.pref_currency_sign}{$invoice.total|number_format:2}</u><br />
	</tr>	
{/if}

</table>
</table>

<hr />
		<b>{$LANG.account_info}</b><br />
		{$LANG.total}: 
		{$preference.pref_currency_sign}{$invoice.total|number_format:2} :: 
		<a href='index.php?module=payments&amp;view=manage&amp;id={$invoice.id}'>{$LANG.paid}:</a> {$preference.pref_currency_sign}{$invoice.paid|number_format:2} :: 
		{$LANG.owing}: <u>{$preference.pref_currency_sign}{$invoice.owing|number_format:2}</u> ::
		{$LANG.age}: {$invoice_age} 
		<br />
<hr />
