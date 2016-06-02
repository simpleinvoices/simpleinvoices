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

    <div class="si_filters si_buttons_invoice_header">
    	<span class="si_filters_links">
	    	<a href="index.php?module=invoices&amp;view=itemised" class="first{if $view=='itemised'} selected{/if}"><img class="action" src="./images/common/edit.png"/>{$LANG.itemised_style}</a>
	    	<a href="index.php?module=invoices&amp;view=total" class="{if $view=='total'}selected{/if}"><img class="action" src="./images/common/page_white_edit.png"/>{$LANG.total_style}</a>
		</span>
    	<span class="si_filters_title">
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_types" title="{$LANG.invoice_type}"><img class="" src="./images/common/help-small.png" alt="" /></a>
		</span>
	</div>



	<table class='si_invoice_top'>
	   <tr>
		  <th>{$LANG.biller}</th>
		   <td>
			   {if $billers == null }
				  <p><em>{$LANG.no_billers}</em></p>
			   {else}
					<select name="biller_id">
					{foreach from=$billers item=biller}
						<option {if $biller.id == $defaults.biller} selected {/if} value="{$biller.id|htmlsafe}">{$biller.name|htmlsafe}</option>
					{/foreach}
					</select>
				{/if}
			</td>
		</tr>
		<tr>
			<th>{$LANG.customer}</th>
			<td>
				{if $customers == null }
				<em>{$LANG.no_customers}</em>
				{else}
					<select name="customer_id">
					{foreach from=$customers item=customer}
						<option {if $customer.id == $defaults.customer} selected {/if} value="{$customer.id|htmlsafe}">{$customer.name|htmlsafe}</option>
					{/foreach}
					</select>
				{/if}
			</td>
		</tr>
		<tr>
          <th>{$LANG.date_formatted}</th>
          <td>
            <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date1" 
                 {if $smarty.get.date}
                   value="{$smarty.get.date}" />
                 {else}
                   value='{$smarty.now|date_format:"%Y-%m-%d"}' />
                 {/if}
          </td>
		</tr>
	</table>

