{*
	/*
	* Script: header.tpl
	* 	 Header file for invoice template
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
<input type="hidden" name="action" value="insert" />
<table align="center">
<tr>
	<td class="details_screen">{$LANG.biller}</td>
	<td><input type="text" name="biller_block" size="25" />
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
	<td class="details_screen">{$LANG.customer_short}</td>
	<td><input type="text" name="customer_block" size="25" />
		
	{if $customers == null }
		<p><em>{$LANG.no_customers}</em></p>
	{else}
		<select name="customer_id">
		{foreach from=$customers item=customer}
			<option {if $customer.id == $defaultCustomerID} selected {/if} value="{$customer.id}">{$customer.name}</option>
		{/foreach}
		</select>
	{/if}

	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.date_upper}</td>
	<td><input type="text" class="date-picker" name="date" id="date1" value='{$smarty.now|date_format:"%Y-%m-%d"}' /></td>
</tr>

