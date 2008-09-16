{*
/*
* Script: total.tpl
* 	 Total style invoice template
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

<form name="frmpost" action="index.php?module=invoices&view=save" method=POST onsubmit="return frmpost_Validator(this)">

<h3>{$LANG.inv} {$LANG.inv_total}</h3>

{include file="$path/header.tpl" }

<tr>
<td class="details_screen">{$LANG.description}</td>
</tr>

<tr>
	<td colspan=5 class="details_screen" ><textarea input type=text name="description" rows=10 cols=100 WRAP=nowrap></textarea></td>
</tr>

	{$show_custom_field.1}
	{$show_custom_field.2}
	{$show_custom_field.3}
	{$show_custom_field.4}
	
	{showCustomFields categorieId="4"}




<tr>
	<td class="details_screen">{$LANG.gross_total}</td><td class="details_screen">{$LANG.tax}</td><td class="details_screen">{$LANG.inv_pref}</td>
</tr>
<tr>
	<td><input type="text" name="unit_price" size="15"></td><td input type=text name="tax" size=15>
	
	{if $taxes == null }
	<p><em>{$LANG.no_taxes}</em></p>
{else}
	<select name="tax_id">
	{foreach from=$taxes item=tax}
		<option {if $tax.tax_id == $defaults.tax} selected {/if} value="{$tax.tax_id}">{$tax.tax_description}</option>
	{/foreach}
	</select>
{/if}

</td><td>

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
	
<tr>
	<td align=left>
		<a class="cluetip" href="#"	rel="docs.php?t=help&p=custom_fields" title="{$LANG.want_more_fields}"><img src="./images/common/help-small.png"></img> {$LANG.want_more_fields}</a>
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
	<input type=hidden name="type" value="1">
</div>
</form>
