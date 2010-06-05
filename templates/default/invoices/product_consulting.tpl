{*
/*
* Script: consulting.tpl
* 	 Consulting invoice type template
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
<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post" onsubmit="return frmpost_Validator(this);">

<h3>{$LANG.inv} {$LANG.inv_consulting}</h3>

{include file="$path/header.tpl" }

<tr>
<td class="details_screen">{$LANG.quantity}</td>
<td class="details_screen">{$LANG.description}</td>
<td class="details_screen">{$LANG.Price}</td>

</tr>


        {section name=line start=0 loop=$dynamic_line_items step=1}

			<tr>
				<td><input type="text" name="quantity{$smarty.section.line.index|htmlsafe}" size="5" /></td>
				<td><input type="text" name="description{$smarty.section.line.index|htmlsafe}" size="50" /></td>
				<td><input type="text" name="price{$smarty.section.line.index|htmlsafe}" size="50" /></td>
            </tr>
                
			<tr class="text{$smarty.section.line.index|htmlsafe} hide">
        		<td colspan="3" ><textarea input type="text" class="editor"  name='notes{$smarty.section.line.index|htmlsafe}' rows="3" cols="80" wrap="nowrap"></textarea></td>
			</tr>

        {/section}
	{$show_custom_field.1}
	{$show_custom_field.2}
	{$show_custom_field.3}
	{$show_custom_field.4}
	{showCustomFields categorieId="4"}



<tr>
        <td colspan="2" class="details_screen">{$LANG.notes}</td>
</tr>

<tr>
        <td colspan="2"><textarea input type="text" class="editor" name="note" rows="5" cols="70" wrap="nowrap"></textarea></td>
</tr>

<tr><td class="details_screen">{$LANG.tax}</td><td><input type="text" name="tax" size="15" />

{if $taxes == null }
	<p><em>{$LANG.no_taxes}</em></p>
{else}
	<select name="tax_id">
	{foreach from=$taxes item=tax}
		<option {if $tax.tax_id == $defaults.tax} selected {/if} value="{$tax.tax_id|htmlsafe}">{$tax.tax_description|htmlsafe}</option>
	{/foreach}
	</select>
{/if}

</td>
</tr>

<tr>
<td class="details_screen">{$LANG.inv_pref}</td><td><input type="text" name="preference_id" />

{if $preferences == null }
	<p><em>{$LANG.no_preferences}</em></p>
{else}
	<select name="preference_id">
	{foreach from=$preferences item=preference}
		<option {if $preference.pref_id == $defaults.preference} selected {/if} value="{$preference.pref_id|htmlsafe}">{$preference.pref_description|htmlsafe}</option>
	{/foreach}
	</select>
{/if}

</td>
</tr>	
<tr>
	<td align=left>
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.Custom_Fields}"><img src="./images/common/help-small.png" alt="" /> {$LANG.want_more_fields}</a>
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
	<input type="hidden" name="max_items" value="{$smarty.section.line.index|htmlsafe}" />
	<input type="submit" name="submit" value="{$LANG.save_invoice}" />
	<input type="hidden" name="type" value="4" />
</div>
</form>
