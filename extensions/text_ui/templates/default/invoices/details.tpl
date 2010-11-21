{*
/*
* Script: details.tpl
* 	 Invoice details template
*
* Last edited:
* 	 2008-09-01
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
        	<img src="images/common/gmail-loader.gif" alt="Loading ..." /> Loading ...
</div>
<b>You are editing {$preference.pref_inv_wording} {$invoice.id}</b>

<br />
--<br />



<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post">

	<table align="center">
	<tr>
		<td colspan="6" align="center"></td>
	</tr>
	<tr>
		<td class="details_screen">{$preference.pref_inv_wording} {$LANG.number_short}</td>
		<td><input type="hidden" name="invoice_id" value={$invoice.id} size="15" />{$invoice.id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.date_upper}</td>
		<td><input type="text" class="date-picker" name="date" id="date1" value='{$invoice.calc_date}' /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.biller}</td><td>
			
		{if $billers == null }
			<p><em>{$LANG.no_billers}</em></p>
		{else}
			<select name="biller_id">
			{foreach from=$billers item=biller}
				<option {if $biller.id == $invoice.biller_id} selected {/if} value="{$biller.id}">{$biller.name}</option>
			{/foreach}
			</select>
		{/if}
					
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer}</td><td>
		
			{if $customers == null}
	        <p><em>{$LANG.no_customers}</em></p>
		
			{else}
			
			<select name="customer_id">
			{foreach from=$customers item=customer}
				<option {if $customer.id == $invoice.customer_id} selected {/if} value="{$customer.id}">{$customer.name}</option>
			{/foreach}
			</select>
		
			{/if}
		
		</td>
	</tr>
{*
	<tr>
		<td class="details_screen">Invoice Status</td>
		<td>
			<select name="status_id">
				<option value="0">New</option>
				<option {if $invoice.status_id == 1} selected{/if} value="1">Sent</option>
				<option {if $invoice.status_id == 2} selected{/if} value="1">Paid</option>
			</select>
		</td>
	</tr>
*}

{if $invoice.type_id == 1 }
	        <tr>
        	        <td colspan="6" class="details_screen">{$LANG.description}</td>
	        </tr>
	        <tr>
			<td colspan="6" ><textarea input type="text" name="description0" rows="10" cols="70" wrap="nowrap">{$invoiceItems.0.description}</textarea></td>
        	</tr>

	 {$customFields.1}
	 {$customFields.2}
	 {$customFields.3}
	 {$customFields.4}
	{* {showCustomFields categorieId="4" itemId=$smarty.get.invoice} *}

	
		        <tr>       	         
			<td class="details_screen">{$LANG.gross_total}</td>
			<td><input type="text" name="unit_price" value="{$invoiceItems.0.unit_price}" size="10" />
			<input type="hidden" name="quantity0" value="1" />
			<input type="hidden" name="id0" value="{$invoiceItems.0.id}" />
			<input type="hidden" name="products0" value="{$invoiceItems.0.product_id}" />
			
			</td>
			
		</tr>
		<tr>

{/if}

{if $invoice.type_id == 2 || $invoice.type_id == 3 }

     {if $invoice.type_id == 2 }
		<tr>
		<td colspan="6">
		<table>
		<tr>
        	        <td class="details_screen">{$LANG.quantity_short}</td>
        	        <td class="details_screen">{$LANG.description}</td>
        	        <td class="details_screen">{$LANG.attribute_short} 1</td>
        	        <td class="details_screen">{$LANG.attribute_short} 2</td>
        	        <td class="details_screen">{$LANG.unit_price}</td>
	        </tr>
	{/if}

        {if $invoice.type_id == 3}
		<tr>
		<td colspan="6">
		<table>
                <tr>
                        <td class="details_screen">{$LANG.quantity_short}</td><td class="details_screen">{$LANG.item}</td>
                </tr>
        {/if}
	
{foreach key=line from=$invoiceItems item=invoiceItem}
		
		
	        <tr>
				<td>
					<input type="text" name='quantity{$line}' value='{$invoiceItem.quantity|number_format:2}' size="10" />
					<input type="hidden" name='id{$line}' value='{$invoiceItem.id}' size="10" /> 
				</td>
			    <td>
					{if $products == null }
						<p><em>{$LANG.no_products}</em></p>
					{else}

						<select
							class="product_select{$line} selector" 
							name="products{$line}"
							onchange="
								invoice_product_change_price($(this).val(), {$line}, jQuery('#quantity{$line}').val() );
								chain_select($(this).val(),'#attr1-'+{$line}, 'attr1', {$line},  jQuery('#quantity{$line}').val() );
								chain_select($(this).val(),'#attr2-'+{$line}, 'attr2', {$line},  jQuery('#quantity{$line}').val() );
								chain_select($(this).val(),'#attr3-'+{$line}, 'attr3', {$line},  jQuery('#quantity{$line}').val() );
									"	
						>
						
						
							{foreach from=$products item=product}
								<option {if $product.id == $invoiceItem.product_id} selected {/if} value="{$product.id}">{$product.description}</option>
							{/foreach}
						</select>
					{/if}
                </td>
	            <td>
				<select id="attr1-{$line}" name="attr1-{$line}" class="linkSel">
					<option value="{$invoiceItem.attr1.id}">{$invoiceItem.attr1.display}</option>
					{foreach  from=$invoiceItem.attr_all_1 item=invoiceItemAll1}
						<option value="{$invoiceItemAll1.id}">{$invoiceItemAll1.display}</option>
					{/foreach}
				</select>
				</td>
				<td>
				<select id="attr2-{$line}" name="attr2-{$line}" class="linkSel" >
					<option value="{$invoiceItem.attr2.id}">{$invoiceItem.attr2.display}</option>
					{foreach  from=$invoiceItem.attr_all_2 item=invoiceItemAll2}
						<option value="{$invoiceItemAll2.id}">{$invoiceItemAll2.display}</option>
					{/foreach}
				</select>
			</td>	
			{if $number_of_attributes == "3"}
			<td>
				<select id="attr3-{$line}" name="attr3-{$line}" class="linkSel" >
					<option value="{$invoiceItem.attr3.id}">{$invoiceItem.attr3.display}</option>
					{foreach from=$invoiceItem.attr_all_3 item=invoiceItemAll3}
						<option value="{$invoiceItemAll3.id}">{$invoiceItemAll3.display}</option>
					{/foreach}
				</select>
			</td>	
			{/if}
				<td>
					<input id="unit_price{$line}" name="unit_price{$line}" size="7" value="{$invoiceItem.unit_price|number_format:2}" />
				</td>
	        </tr>
		
	                
	                
	        </tr>
		

	{if $invoice.type_id == 3}
		

		<tr>

			<td colspan="6" class="details_screen">{$LANG.description}</td>
		<tr>
                        <td colspan="6"><textarea input type="text" name="description{$line}" rows="5" cols="70" wrap="nowrap">{$invoiceItem.description}</textarea></td>
                </tr>
	
	{/if}
{/foreach}

	<tr>
		<td>
			<a href="./index.php?module=invoices&amp;view=add_invoice_item&amp;invoice={$invoice.id}&amp;type={$invoice.type_id}&amp;tax_id={$invoiceItems.0.tax_id}"><img src="./images/common/famfamAdd.png" alt="" />{$LANG.add_invoice_item}</a>
		</td>
		<td>
		</td>
	</tr>

	 {$customFields.1}
	 {$customFields.2}
	 {$customFields.3}
	 {$customFields.4}

	{/if}
	
	
	<tr>
		<td class="details_screen">{$LANG.tax}</td>
		<td>
	                         
	                         	
{if $taxes == null }
	<p><em>{$LANG.no_taxes}</em></p>
{else}
	<select name="tax_id">
	{foreach from=$taxes item=tax}
		<option {if $tax.tax_id == $invoiceItems.0.tax_id} selected {/if} value="{$tax.tax_id}">{$tax.tax_description}</option>
	{/foreach}
	</select>
{/if}


	</td>
	</tr>
	<td class="details_screen">{$LANG.inv_pref}</td><td>


{if $preferences == null }
	<p><em>{$LANG.no_preferences}</em></p>
{else}
	<select name="preference_id">
	{foreach from=$preferences item=preference}
		<option {if $preference.pref_id == $invoice.preference_id} selected {/if} value="{$preference.pref_id}">{$preference.pref_description}</option>
	{/foreach}
	</select>
{/if}
	                         
	                         </td>
	                </tr>

	


        </table>
	<!-- addition close table tag to close invoice itemised/consulting if it has a note -->
	</table>

--
<br />
	<input type="hidden" name="action" value="edit" />
	<input type="hidden" name="type" value="{$invoice.type_id}" />
	<input type="button" value="{$LANG.cancel}" onclick="javascript: history.back()" />
	<input type="submit" name="submit" value="{$LANG.save}" />
	<input type="hidden" name="max_items" value="{$lines}" />
</form>
