{*
/*
* Script: total.tpl
* 	 Total style invoice template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="POST">
<!--
<h3>{$LANG.inv} {$LANG.inv_total}</h3>
-->

<div class="si_toolbar si_toolbar_form" style="float: right;">
	<button type="submit" class="invoice_save" name="submit" value="{$LANG.save}"><img class="button_img" src="./images/common/tick.png" alt="tick" />{$LANG.save}</button>
</div>

<div class="si_invoice_form">


{include file="$path/header.tpl" }

	<table id="itemtable" class="si_invoice_items">
		<tr>
			<td class="si_invoice_notes" colspan="5">
				<h5>{$LANG.description}</h5>
				<textarea input type="text" class="editor" name="description" rows="10" cols="100" wrap="nowrap"></textarea>
			</td>
		</tr>
	</table>

	<table class="si_invoice_bot">

		<tr class="si_invoice_total">
			<th class="">{$LANG.gross_total}</th>
			{section name=tax_header loop=$defaults.tax_per_line_item }
				<th class="">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax_header.index+1|htmlsafe}{/if} </th>
			{/section}
			<th class="">{$LANG.inv_pref}</th>
		</tr>

		<tr class="si_invoice_total">
			<td><input type="text" class="validate[required]" name="unit_price" size="15" /></td>
		{if $taxes == null }
			<td><p><em>{$LANG.no_taxes}</em></p></td>
		{else}
			{section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
			<td>				                				                
				<select id="tax_id[0][{$smarty.section.tax.index|htmlsafe}]" name="tax_id[0][{$smarty.section.tax.index|htmlsafe}]">
					<option value=""></option>
				{foreach from=$taxes item=tax}
					<option {if $tax.tax_id == $defaults.tax AND $smarty.section.tax.index == 0} selected {/if}   value="{$tax.tax_id|htmlsafe}">{$tax.tax_description|htmlsafe}</option>
				{/foreach}
				</select>
			</td>
			{/section}
		{/if}
		
			<td>
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

	{$show_custom_field.1}
	{$show_custom_field.2}
	{$show_custom_field.3}
	{$show_custom_field.4}


	</table>



	<div class="si_toolbar si_toolbar_form">
			<button type="submit" class="positive" name="submit" value="{$LANG.save}"><img class="button_img" src="./images/common/tick.png" alt="tick" />{$LANG.save}</button>
			<a href="./index.php?module=invoices&amp;view=manage" class="negative"><img src="./images/common/cross.png" alt="cross" />{$LANG.cancel}</a>
	</div>

	<div class="si_help_div">
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{$LANG.want_more_fields}"><img src="./images/common/help-small.png" alt="help" /> {$LANG.want_more_fields}</a>
	</div>

</div>
<input type="hidden" name="max_items" value="{$smarty.section.line.index|htmlsafe}" />
<input type="hidden" name="type" value="1" />

</form>
