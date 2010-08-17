{*
/*
* Script: itemised.tpl
* 	 Itemised invoice template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
        	<img src="images/common/gmail-loader.gif" alt="Loading ..." /> Loading ...
</div>
<form name="frmpost" action="index.php?module=invoices&view=save" method="post" onsubmit="return frmpost_Validator(this)">

{include file="$path/header.tpl" }

<tr>
	<td class="details_screen">{$LANG.quantity_short}</td>
	<td class="details_screen">{$LANG.description_short}</td>
	<td class="details_screen">{$LANG.attribute_short}1</td>
	<td class="details_screen">{$LANG.attribute_short}2</td>
	{if $number_of_attributes == "3"}
	<td class="details_screen">{$LANG.attribute_short}3</td>
	{/if}
</tr>


        {section name=line start=0 loop=$dynamic_line_items step=1}

			<tr>
				<td><input type="text"  id="quantity{$smarty.section.line.index}" name="quantity{$smarty.section.line.index}" size="5"></td>
				<td>
				                
			{if $products == null }
				<p><em>{$LANG.no_products}</em></p>
			{else}
				<select
					class="product_select{$smarty.section.line.index} selector" 
					name="products{$smarty.section.line.index}"
					{* onchange="invoice_product_change_price($(this).val(), {$smarty.section.line.index}, jQuery('#quantity{$smarty.section.line.index}').val() );" *}
					onchange="
						invoice_product_change_price($(this).val(), {$smarty.section.line.index}, jQuery('#quantity{$smarty.section.line.index}').val() );
						chain_select($(this).val(),'#attr1-'+{$smarty.section.line.index}, 'attr1', {$smarty.section.line.index},  jQuery('#quantity{$smarty.section.line.index}').val() );
						chain_select($(this).val(),'#attr2-'+{$smarty.section.line.index}, 'attr2', {$smarty.section.line.index},  jQuery('#quantity{$smarty.section.line.index}').val() );
						chain_select($(this).val(),'#attr3-'+{$smarty.section.line.index}, 'attr3', {$smarty.section.line.index},  jQuery('#quantity{$smarty.section.line.index}').val() );
							"	
				>
					<option value=""></option>
				{foreach from=$products item=product}
					<option {if $product.id == $defaults.product} selected {/if} value="{$product.id}">{$product.description}</option>
				{/foreach}
				</select>
			{/if}
				                				                
        		        </td>
				<td>
				<select id="attr1-{$smarty.section.line.index}" name="attr1-{$smarty.section.line.index}" class="linkSel" disabled="disabled">
					<option value="">--  --</option>
				</select>
				</td>
				<td>
				<select id="attr2-{$smarty.section.line.index}" name="attr2-{$smarty.section.line.index}" class="linkSel" disabled="disabled">
					<option value="">--  --</option>
				</select>
				</td>	
			{if $number_of_attributes == "3"}
				<td>
				<select id="attr3-{$smarty.section.line.index}" name="attr3-{$smarty.section.line.index}" class="linkSel" disabled="disabled">
					<option value="">--  --</option>
				</select>
				</td>	
			{/if}
				<td><input id="unit_price{$smarty.section.line.index}" name="unit_price{$smarty.section.line.index}" size="7" value="" /></td>
{*
				<td>
					<select name="products{$smarty.section.line.index}">
						<option value=""></option>
						{foreach from=$matrix item=matrix_item}
							<option {if $product.id == $defaults.product} selected {/if} value="{$matrix_item.id}">{$matrix_item.display}</option>
						{/foreach}
					</select>
				</td>	
				<td>
					<select name="products{$smarty.section.line.index}">
						<option value=""></option>
						{foreach from=$matrix item=matrix_item}
							<option {if $product.id == $defaults.product} selected {/if} value="{$matrix_item.id}">{$matrix_item.display}</option>
						{/foreach}
					</select>
				</td>	
*}
		</tr>

        {/section}
	{$show_custom_field.1}
	{$show_custom_field.2}
	{$show_custom_field.3}
	{$show_custom_field.4}
	{showCustomFields categorieId="4" itemId=""}


{*
<tr>
        <td colspan="2" class="details_screen">{$LANG.notes}</td>
</tr>

<tr>
        <td colspan="2"><textarea input type="text" name="note" rows="5" cols="70" wrap="nowrap"></textarea></td>
</tr>
*}
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
</tr>

<tr>
<td class="details_screen">{$LANG.inv_pref_short}</td>
<td><input type="text" name="preference_id" />

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
<!--Add more line items while in an itemeised invoice - Get style - has problems- wipes the current values of the existing rows - not good
<tr>
<td>
<a href="?get_num_line_items=10">Add 5 more line items</a>
</tr>
-->
</table>
<!-- </div> -->
<hr />
<div style="text-align:center;">
	<input type="hidden" name="max_items" value="{$smarty.section.line.index}" />
	<input type="submit" name="submit" value="{$LANG.save_invoice}" />
	<input type="hidden" name="type" value="2" />
</div>
</form>
