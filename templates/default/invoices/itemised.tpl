{*
/*
* Script: itemised.tpl
* 	 Itemised invoice template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<form name="frmpost" action="index.php?module=invoices&view=save" method="POST" onsubmit="return frmpost_Validator(this)">

<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
        	<img src="images/common/gmail-loader.gif" alt="Loading ..." /> Loading ...
</div>

{include file="$path/header.tpl" }
<table align="left">
	<tr>
		<td colspan="3">
		
		<table id="itemtable">
			<tbody id="itemtable-tbody">
			<tr>
				<td class="details_screen"></td>
				<td class="details_screen">{$LANG.quantity}</td>
				<td class="details_screen">{$LANG.item}</td>
				{section name=tax_header loop=$defaults.tax_per_line_item }
					<td class="details_screen">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax_header.index+1}{/if} </td>
				{/section}
				<td class="details_screen">{$LANG.unit_price}</td>
				<td>
					<a 
						href='#' 
						class="show-note" 
						onClick="$('.note').show();$('.show-note').hide();"
					>
						<img src="./images/common/page_white_add.png" title="{$LANG.show_details}" alt="" />
					</a>
					<a href='#' class="note" onClick="$('.note').hide();$('.show-note').show();">
						<img src="./images/common/page_white_delete.png" title="{$LANG.hide_details}" alt="" />
					</a>
				</td>
			</tr>
			</tbody>
	
	        {section name=line start=0 loop=$dynamic_line_items step=1}
				<tbody class="line_item" id="row{$smarty.section.line.index}">
					<tr>
						<td>
							{if $smarty.section.line.index == "0"}
							<a 
								href="#" 
								class="trash_link"
								id="trash_link{$smarty.section.line.index}"
								title="The first row can not be deleted"
							>
								<img 
									id="trash_image{$smarty.section.line.index}"
									src="./images/common/blank.gif"
									height="16px"
									width="16px"
									title="The first row can not be deleted"
									alt=""
								 />
							</a>
							{/if}
							{if $smarty.section.line.index != 0}
							{* can't delete line 0 *}
							<!-- onclick="delete_row({$smarty.section.line.index});" --> 
							<a 
								id="trash_link{$smarty.section.line.index}"
								class="trash_link"
								title="Delete this row" 
								rel="{$smarty.section.line.index}"
								href="#" 
								style="display: inline;"
							>
								<img src="./images/common/delete_item.png" alt="" />
							</a>
							{/if}
						</td>
						<td>
							<input type="text" name="quantity{$smarty.section.line.index}" id="quantity{$smarty.section.line.index}" size="5" /></td>
						<td>
										
					{if $products == null }
						<p><em>{$LANG.no_products}</em></p>
					{else}
					{* onchange="invoice_product_change_price($(this).val(), {$smarty.section.line.index}, jQuery('#quantity{$smarty.section.line.index}').val() );" *}
						<select 
							id="products{$smarty.section.line.index}"
							name="products{$smarty.section.line.index}"
							rel="{$smarty.section.line.index}"
							class="product_change"
												>
							<option value=""></option>
						{foreach from=$products item=product}
							<option {if $product.id == $defaults.product} selected {/if} value="{$product.id}">{$product.description}</option>
						{/foreach}
						</select>
					{/if}
						</td>
						{section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
						<td>				                				                
							<select 
								id="tax_id[{$smarty.section.line.index}][{$smarty.section.tax.index}]"
								name="tax_id[{$smarty.section.line.index}][{$smarty.section.tax.index}]"
							>
							<option value=""></option>
							{foreach from=$taxes item=tax}
								<option value="{$tax.tax_id}">{$tax.tax_description}</option>
							{/foreach}
						</select>
						</td>
						{/section}
						<td>
							<input 
								id="unit_price{$smarty.section.line.index}" 
								name="unit_price{$smarty.section.line.index}" 
								size="7"
								value=""
							/>
						</td>	
					</tr>
							
					<tr class="note">
							<td>
							</td>
							<td colspan="4">
								<textarea input type="text" class="note" name="description{$smarty.section.line.index}" id="description{$smarty.section.line.index}" rows=3 cols=3 WRAP=nowrap></textarea>
								
								</td>
					</tr>
				</tbody>
	        {/section}
			{$show_custom_field.1}
			{$show_custom_field.2}
			{$show_custom_field.3}
			{$show_custom_field.4}
			{*
				{showCustomFields categorieId="4" itemId=""}
			*}
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="buttons" align="left">
				<tr>
					<td>
						{* onClick="add_line_item();" *}
						<a 
							href="#" 
							class="add_line_item"
						>
							<img 
								src="./images/common/add.png"
								alt=""
							/>
							Add new row{* $LANG TODO *}
						</a>
				
					</td>
				</tr>
			 </table>
		</td>
	</tr>
	<tr>
	        <td colspan="1" class="details_screen">{$LANG.notes}</td>
	</tr>
	
	<tr>
	        <td colspan="4">
	        	<textarea input type="text" class="editor" name="note" rows="5" cols="50" wrap="nowrap"></textarea>
	        	
	        	</td>
	</tr>
	{*
	<tr><td class="details_screen">{$LANG.tax}</td>
	<td>
	
	
	{if $taxes == null }
		<p><em>{$LANG.no_taxes}</em></p>
	{else}
		<select name="tax_id">
		{foreach from=$taxes item=tax}
			<option {if $tax.tax_id == $defaults.tax} selected {/if} value="{$tax.tax_id}">{$tax.tax_description}</option>
		{/foreach}
		</select>
	{/if}
	
	</td>
	*}
	</tr>
	
	<tr>
	<td class="details_screen">{$LANG.inv_pref}</td>
	<td> 
	{if $preferences == null }
		<p><em>{$LANG.no_preferences}</em></p>
	{else}
		<select name="preference_id">
		{foreach from=$preferences item=preference}
			<option {if $preference.pref_id == $defaults.preference} selected {/if} value="{$preference.pref_id}">{$preference.pref_description}</option>
		{/foreach}
		</select>
	{/if}
	
	</td>
	</tr>	
	<tr>
		<td class=""> 
			<a class="cluetip" href="#"	rel="docs.php?t=help&p=invoice_custom_fields" title="{$LANG.want_more_fields}"><img src="./images/common/help-small.png" alt="" /> {$LANG.want_more_fields}</a>
		</td>
	</tr>

</table>
</td>
</tr>
<tr>
<td>
	<table class="buttons" align="center">
	    <tr>
	        <td>
	            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
	                <img class="button_img" src="./images/common/tick.png" alt="" /> 
	                {$LANG.save}
	            </button>
	            
			</td>
			<td>
				<input type="hidden" id="max_items" name="max_items" value="{$smarty.section.line.index}" />
	        	<input type="hidden" name="type" value="2" />
	        	
	            <a href="./index.php?module=invoices&view=manage" class="negative">
	                <img src="./images/common/cross.png" alt="" />
	                {$LANG.cancel}
	            </a>
	    
	        </td>
	    </tr>
	 </table>
	 
</td>
</tr>
</table>

</form>
