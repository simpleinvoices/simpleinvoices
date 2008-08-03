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
{literal}
<script type="text/javascript" charset="utf-8">

$(function(){

$('.product_select').linkedSelect('./index.php?module=invoices&view=ajaxt&search=country','.linkSel',{firstOption: 'Please Select A State'});


$('#state').linkedSelect('./index.php?module=invoices&view=ajax&search=state','#city',{firstOption: 'Please Select A City', loadingText: 'Loading Please Wait...'});

});

</script>
{/literal}

<form name="frmpost" action="index.php?module=invoices&view=save" method=POST onsubmit="return frmpost_Validator(this)">

<h3>{$LANG.inv} {$LANG.inv_itemised}</h3>

{include file="$path/header.tpl" }

<tr>
<td class="details_screen">{$LANG.quantity}</td><td class="details_screen">{$LANG.description}</td><td class="details_screen">Attribute</td><td class="details_screen">Value</td>
</tr>


        {section name=line start=0 loop=$dynamic_line_items step=1}

			<tr>
				<td><input type=text name="quantity{$smarty.section.line.index}" size="5"></td>
				<td>
				                
			{if $products == null }
				<p><em>{$LANG.no_products}</em></p>
			{else}
				<select class="product_select" name="products{$smarty.section.line.index}">
					<option value=""></option>
				{foreach from=$products item=product}
					<option {if $product.id == $defaults.product} selected {/if} value="{$product.id}">{$product.description}</option>
				{/foreach}
				</select>
			{/if}
				                				                
                </td>
			<td>
<select id="state" class="linkSel" name="state" disabled="disabled">
<option value="">--  --</option>
</select><br /><br />

<label for="city">City</label><br />
<select id="city" class="linkSel" name="city" disabled="disabled">
<option value="">--  --</option>
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
				<td>
					<select name="products{$smarty.section.line.index}">
						<option value=""></option>
						{foreach from=$matrix item=matrix_item}
							<option {if $product.id == $defaults.product} selected {/if} value="{$matrix_item.id}">{$matrix_item.display}</option>
						{/foreach}
					</select>
				</td>	
		</tr>

        {/section}
	{$show_custom_field.1}
	{$show_custom_field.2}
	{$show_custom_field.3}
	{$show_custom_field.4}
	{showCustomFields categorieId="4" itemId=""}



<tr>
        <td colspan=2 class="details_screen">{$LANG.notes}</td>
</tr>

<tr>
        <td colspan=2><textarea input type=text name="note" rows=5 cols=70 WRAP=nowrap></textarea></td>
</tr>

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
<td class="details_screen">{$LANG.inv_pref}</td><td input type=text name="preference_id">

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
	<td align=left>
		<a href="docs.php?t=help&p=invoice_custom_fields" rel="gb_page_center[450, 450]">{$LANG.want_more_fields}<img src="./images/common/help-small.png"></img></a>

	</td>
</tr>
<!--Add more line items while in an itemeised invoice - Get style - has problems- wipes the current values of the existing rows - not good
<tr>
<td>
<a href="?get_num_line_items=10">Add 5 more line items<a>
</tr>
-->
</table>
<!-- </div> -->
<hr />
<div style="text-align:center;">
	<input type=hidden name="max_items" value="{$smarty.section.line.index}">
	<input type=submit name="submit" value="{$LANG.save_invoice}">
	<input type=hidden name="type" value="2">
</div>
</form>
