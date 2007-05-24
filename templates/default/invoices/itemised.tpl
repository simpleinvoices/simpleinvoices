<form name="frmpost" action="index.php?module=invoices&view=save" method=POST onsubmit="return frmpost_Validator(this)">

<h3>{$LANG.inv} {$LANG.inv_itemised}</h3>
<hr />


<table align=center>


<tr>
	<td class="details_screen">
		{$LANG.biller_name}
	</td>
	<td input type=text name="biller_block" size=25>
		{if $billers == null }
	<p><em>{$LANG.no_billers}</em></p>
{else}
	<select name="biller_id">
	{foreach from=$billers item=biller}
		<option {if $biller.id == $defaults.biller} selected {/if} value="{$biller.id}">{$biller.name}</option>
	{/foreach}
	</select>
{/if}

	</td>
</tr>
<tr>
	<td class="details_screen">
		{$LANG.customer_name}
	</td>
	<td>
		
{if $customers == null }
	<p><em>{$LANG.no_customers}</em></p>
{else}
	<select name="customer_id">
	{foreach from=$customers item=customer}
		<option {if $customer.id == $defaults.customer} selected {/if} value="{$customer.id}">{$customer.name}</option>
	{/foreach}
	</select>
{/if}


	</td>
</tr>
<tr>
        <td class="details_screen">{$LANG.date_formatted}</td>
        <td>
                        <input type="text" class="date-picker" name="date" id="date1" value='{$smarty.now|date_format:"%Y-%m-%d"}'></input>
        </td>
</tr>

<tr>
<td class="details_screen">{$LANG.quantity}</td><td class="details_screen">{$LANG.description}</td>
</tr>


        {section name=line start=0 loop=$dynamic_line_items step=1}

			<tr>
				<td><input type=text name="quantity{$smarty.section.line.index}" size="5"></td><td input type=text name="i_description{$smarty.section.line.index}" size="50">
				                
			{if $products == null }
				<p><em>{$LANG.no_products}</em></p>
			{else}
				<select name="select_products{$smarty.section.line.index}">
					<option value=""></option>
				{foreach from=$products item=product}
					<option {if $product.id == $defaults.product} selected {/if} value="{$product.id}">{$product.description}</option>
				{/foreach}
				</select>
			{/if}
				                				                
                </td></tr>

        {/section}
	{$show_custom_field.1}
	{$show_custom_field.2}
	{$show_custom_field.3}
	{$show_custom_field.4}


<tr>
        <td colspan=2 class="details_screen">{$LANG.notes}</td>
</tr>

<tr>
        <td colspan=2><textarea input type=text name="note" rows=5 cols=70 WRAP=nowrap></textarea></td>
</tr>

<tr><td class="details_screen">{$LANG.tax}</td><td input type=text name="tax" size=15>

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
	<input type=hidden name="style" value="insert_itemised">
</div>
</form>
