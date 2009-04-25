{*
	/*
	* Script: header.tpl
	* 	 Header file for invoice template
	*
	* License:
	*	 GPL v3 or above
	*
	* Website:
	*	http://www.simpleinvoices.org
	*/
#$Id$
*}
<input type="hidden" name="action" value="insert" />
 <div class="details_screen">
  <label for="biller_id">
    {$LANG.biller_name}
  </label>
  {if $billers == null }
    <em>{$LANG.no_billers}</em></p>
	{else}
			<select name="biller_id">
			{foreach from=$billers item=biller}
				<option {if $biller.id == $defaults.biller} selected {/if} value="{$biller.id}">{$biller.name}</option>
			{/foreach}
			</select>
		{/if}
      <br />
			<label for="customer_id">
				{$LANG.customer_name}
			</label>
				{if $customers == null }
				<em>{$LANG.no_customers}</em>
				{else}
					<select name="customer_id">
					{foreach from=$customers item=customer}
						<option {if $customer.id == $defaultCustomerID} selected {/if} value="{$customer.id}">{$customer.name}</option>
					{/foreach}
					</select>
				{/if}
		   <label for="date">{$LANG.date_formatted}</label>
		   <input type="text" class="date-picker" size="10" name="date" id="date1" value='{$smarty.now|date_format:"%Y-%m-%d"}' />   
       <br />
</div>
