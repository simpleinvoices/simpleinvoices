{*
/*
* Script: details.tpl
* 	 Invoice details template
*	 Modified for 'default_invoices' by Marcel van Dorp. Version 20090208
*	 if no invoice_id set, the date will be today, and the action will be 'insert' instead of 'edit'
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{* <h3>You are editing {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}</h3> *}
<br />
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
        	<img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..." /> {$LANG.loading} ...
</div>


<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post">

	<table align="center">
	<tr>
		<td colspan="6" align="center"></td>
	</tr>
        <tr>
		<td class="details_screen">{$preference.pref_inv_wording|htmlsafe} {$LANG.number_short}</td><td> {$invoice.index_id|htmlsafe} </td>
	</tr>
	<tr>
	        <td class="details_screen">{$LANG.date_formatted}</td>
	{if $invoice.id == null} 
        	<td><input type="text" size="10" class="date-picker" name="date" id="date1" value="{$smarty.now|date_format:"%Y-%m-%d"}" /></td>
	{else}
        	<td><input type="text" size="10" class="date-picker" name="date" id="date1" value="{$invoice.calc_date|htmlsafe}" /></td>
	{/if}
	</tr>
	<tr>
		<td class="details_screen">{$LANG.biller}</td><td>
			
		{if $billers == null }
			<p><em>{$LANG.no_billers}</em></p>
		{else}
			<select name="biller_id">
			{foreach from=$billers item=biller}
				<option {if $biller.id == $invoice.biller_id} selected {/if} value="{$biller.id|htmlsafe}">{$biller.name|htmlsafe}</option>
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
				<option {if $customer.id == $invoice.customer_id} selected {/if} value="{$customer.id|htmlsafe}">{$customer.name|htmlsafe}</option>
			{/foreach}
			</select>
		
			{/if}
		
		</td>
	</tr>
	{*
	TODO: implement status 
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
			<td colspan="6" ><textarea input type="text" class="editor" name="description0" rows="10" cols="70" wrap="nowrap">{$invoiceItems.0.description|outhtml}</textarea></td>
        	</tr>

	 {$customFields.1}
	 {$customFields.2}
	 {$customFields.3}
	 {$customFields.4}
	 {*
	 	{showCustomFields categorieId="4" itemId=$smarty.get.invoice}
	 *}

	
		        <tr>       	         
			<td class="details_screen">{$LANG.gross_total}</td>
			<td>
			<input type="text" name="unit_price0" value="{$invoiceItems.0.unit_price|siLocal_number_formatted}" size="10" />
			<input type="hidden" name="quantity0" value="1" />
			<input type="hidden" name="id0" value="{$invoiceItems.0.id|htmlsafe}" />
			<input type="hidden" name="products0" value="{$invoiceItems.0.product_id|htmlsafe}" />
			
			</td>
			
		</tr>
        <tr>
			<td class="details_screen">{$LANG.tax}</td>
                <td>
                    <table>     
                        <tr>
						{section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
							<td>				                				                
								<select 
									id="tax_id[0][{$smarty.section.tax.index|htmlsafe}]"
									name="tax_id[0][{$smarty.section.tax.index|htmlsafe}]"
								>
								<option value=""></option>
								{assign var="index" value=$smarty.section.tax.index}
								{foreach from=$taxes item=tax}
									<option {if $tax.tax_id === $invoiceItems.0.tax.$index} selected {/if} value="{$tax.tax_id|htmlsafe}">{$tax.tax_description|htmlsafe}</option>
								{/foreach}
							</select>
							</td>
						{/section}
                        </tr>
                    </table>
				<td>
        </tr>

{/if}

{if $invoice.type_id == 2 || $invoice.type_id == 3 }


		<tr>
		<td colspan="6">
		
		<table id="itemtable">
			<tr>
				<td class="details_screen"></td>
	        	<td class="details_screen">{$LANG.quantity_short}</td>
	        	<td class="details_screen">{$LANG.description}</td>
				{section name=tax_header loop=$defaults.tax_per_line_item }
					<td class="details_screen">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax_header.index+1|htmlsafe}{/if} </td>
				{/section}
	        	<td class='details_screen'>{$LANG.unit_price}</td>
	        	<td>
					<a 
						href='#' 
						class="show-note" 
						onclick="javascript: $('.note').show();$('.show-note').hide();"
					>
						<img src="./images/common/page_white_add.png" title="{$LANG.show_details}" alt="" />
					</a>
					<a href='#' class="note" onclick="javascript: $('.note').hide();$('.show-note').show();">
						<img src="./images/common/page_white_delete.png" title="{$LANG.hide_details}" alt="" />
					</a>
				</td>
		    </tr>
	
			{foreach key=line from=$invoiceItems item=invoiceItem name=line_item_number}
				<tbody class="line_item" id="row{$line|htmlsafe}">
			        <tr>
						<td>
						{if $line != "0"}
							<a 
								id="trash_link_edit{$line|htmlsafe}"
								class="trash_link_edit"
								title="{$LANG.delete_line_item}" 
								href="#" 
								style="display: inline;"
								rel="{$line|htmlsafe}"
							>
								<img id="delete_image{$line|htmlsafe}" src="./images/common/delete_item.png" alt="" />
							</a>
						{/if}
						{if $line == "0"}
							<a 
								id="trash_link_edit{$line|htmlsafe}"
								class="trash_link_edit"
								title="{$LANG.delete_line_item}"
								href="#"
								style="display: inline;"
								rel="{$line|htmlsafe}"
							>
								<img id="delete_image{$line|htmlsafe}" src="./images/common/blank.gif" alt="" />
							</a>
						{/if}
						</td>
						<td>
							<input type="hidden" id="delete{$line|htmlsafe}" name="delete{$line|htmlsafe}" size="3" />
							<input 
								type="text" 
								name='quantity{$line|htmlsafe}' 
								id='quantity{$line|htmlsafe}' 
								value='{$invoiceItem.quantity|siLocal_number_formatted}' 
								size="10"
							/>
							<input type="hidden" name='line_item{$line|htmlsafe}' id='line_item{$line|htmlsafe}' value='{$invoiceItem.id|htmlsafe}' /> 
						</td>
						<td>
					                
					        {if $products == null }
								<p><em>{$LANG.no_products}</em></p>
							{else}
								{*	onchange="invoice_product_change_price($(this).val(), {$line|htmlsafe}, jQuery('#quantity{$line|htmlsafe}').val() );" *}
								<select 
									name="products{$line|htmlsafe}"
									id="products{$line|htmlsafe}"
									rel="{$line|htmlsafe}"
									class="product_change"
								>
								{foreach from=$products item=product}
									<option {if $product.id == $invoiceItem.product_id} selected {/if} value="{$product.id|htmlsafe}">{$product.description|htmlsafe}</option>
								{/foreach}
								</select>
							{/if}
						</td>
						{section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
							<td>				                				                
								<select 
									id="tax_id[{$line|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
									name="tax_id[{$line|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
								>
								<option value=""></option>
								{assign var="index" value=$smarty.section.tax.index}
								{foreach from=$taxes item=tax}
									<option {if $tax.tax_id === $invoiceItem.tax.$index} selected {/if} value="{$tax.tax_id|htmlsafe}">{$tax.tax_description|htmlsafe}</option>
								{/foreach}
							</select>
							</td>
						{/section}
						<td>
							<input id="unit_price{$line|htmlsafe}" name="unit_price{$line|htmlsafe}" size="7" value="{$invoiceItem.unit_price|siLocal_number_clean}" />
						</td>
			        </tr>
		            	<tr colspan="6" class="note">
								<td>
								</td>
								<td colspan="4">
									<textarea input type="text" class="note-edit" name="description{$line|htmlsafe}" id="description{$line|htmlsafe}" rows="3" cols="3" wrap="nowrap">{$invoiceItem.description|outhtml}</textarea>
									
									</td>
						</tr>
					</tbody>
			{/foreach}
		</table>
		</td>
		</tr>
		<tr>
			<td>
				<table class="buttons" align="left">
					<tr>
						<td>
							{* onclick="add_line_item();" *}
							<a 
								href="#" 
								class="add_line_item"
							>
								<img 
									src="./images/common/add.png"
									alt=""
								/>
								{$LANG.add_new_row}
							</a>
					
						</td>
					</tr>
				 </table>
			</td>
		</tr>

		{*
			<tr>
				<td>
					<a href="./index.php?module=invoices&amp;view=add_invoice_item&amp;invoice={$invoice.id|urlencode}&amp;type={$invoice.type_id|urlencode}&amp;tax_id={$invoiceItems.0.tax_id|urlencode}"><img src="./images/common/famfamAdd.png" alt="" />{$LANG.add_invoice_item}</a>
				</td>
				<td>
				</td>
			</tr>
		*}
	 {$customFields.1}
	 {$customFields.2}
	 {$customFields.3}
	 {$customFields.4}
	 {*
	 	 {showCustomFields categorieId="4" itemId=$smarty.get.invoice}
	 *}
			<tr>
				<td colspan="6" class="details_screen">{$LANG.note}:</td>
			</tr>
			<tr>
	             <td colspan="6" ><textarea input type="text" class="editor" name="note" rows="10" cols="70" wrap="nowrap">{$invoice.note|outhtml}</textarea></td>
			</tr>
			
{/if}

	<tr>
		<td class="details_screen">{$LANG.inv_pref}</td><td>


		{if $preferences == null }
			<p><em>{$LANG.no_preferences}</em></p>
		{else}
			<select name="preference_id">
			{foreach from=$preferences item=preference}
				<option {if $preference.pref_id == $invoice.preference_id} selected {/if} value="{$preference.pref_id|htmlsafe}">{$preference.pref_description|htmlsafe}</option>
			{/foreach}
			</select>
		{/if}
	                         
	    </td>
	</tr>

	


    </table>
	<!-- addition close table tag to close invoice itemised/consulting if it has a note -->
	</table>

<br />
<table class="buttons" align="center">
	<tr>
		<td>
			<button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
				<img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>
			{if $invoice.id == null} 
				<input type="hidden" name="action" value="insert" />
			{else}
				<input type="hidden" name="id" value="{$invoice.id|htmlsafe}" />
				<input type="hidden" name="action" value="edit" />
			{/if}
            {if $invoice.type_id == 1 }
                <input id="quantity0" type="hidden" size="10" value="1.00" name="quantity0"/>
                <input id="line_item0" type="hidden" value="{$invoiceItems.0.id|htmlsafe}" name="line_item0"/>
            {/if}
			<input type="hidden" name="type" value="{$invoice.type_id|htmlsafe}" />
			<input type="hidden" name="op" value="insert_preference" />
			<input type="hidden" id="max_items" name="max_items" value="{$lines|htmlsafe}" />
			<a href="./index.php?module=invoices&amp;view=manage" class="negative">
				<img src="./images/common/cross.png" alt="" />
				{$LANG.cancel}
			</a>
		</td>
	</tr>
</table>
 	
</form>
