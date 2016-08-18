<form name="frmpost" action="index.php?module=payments&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">
<div class="si_form"><!--/simple/extensions/matts_luxury_pack/templates/default/payments-->
	<table>	
	
	{if $smarty.get.op === "pay_selected_invoice"}
	
		<tr>
			<th>{$invoice.preference|htmlsafe}</th>
			<td>{$invoice.index_id|htmlsafe}</td>
			<th class="details_screen">{$LANG.total}</th>
			<td>{$invoice.total|number_format:2}</td>
		</tr>
		<tr>
			<th>{$LANG.biller}</th>
			<td>{$biller.name|htmlsafe}</td>
			<th>{$LANG.paid}</th>
			<td>{$invoice.paid|number_format:2}</td>
		</tr>
		<tr>
			<th>{$LANG.customer}</th>
			<td>{$customer.name|htmlsafe}</td>
			<th>{$LANG.owing}</th>
			<td><u>{$invoice.owing|number_format:2}</u></td>
		</tr>
		<tr>
			<th>{$LANG.amount}</th>
			<td colspan="5">
				<input type="text" name="ac_amount" size="25" value="{$invoice.owing|number_format:2|replace:',':''}{*siLocal_number*}{*htmlsafe*}" />
				{assign var=int value=$invoice.owing|replace:',':''}<!-- {$int} -->{*preg_replace:"/[^0-9]/":""*}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_auto_amount" title="{$LANG.process_payment_auto_amount}"><img src="./images/common/help-small.png" alt="" /></a>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>{$LANG.date_formatted}</th>
			<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|htmlsafe}" /></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		
	{/if}
		
	{if $smarty.get.op === "pay_invoice"}

		<tr>
			<th>{$LANG.invoice}</th>
			<td>
				<select name="invoice_id" class="validate[required]">
					<option value=''></option>
			{foreach from=$invoice_all item=invoice}
					<option value="{$invoice.id|htmlsafe}">{$invoice.index_name|htmlsafe} ({$invoice.biller|htmlsafe}, {$invoice.customer|htmlsafe}, {$LANG.total} {$invoice.invoice_total|siLocal_number} : {$LANG.owing} {$invoice.owing|siLocal_number})</option>
			{/foreach}
				</select>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>{$LANG.amount}</th>
			<td colspan="5"><input type="text" name="ac_amount" size="25" /></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<div class="demo-holder">
				<th class="details_screen">{$LANG.date_formatted}</th>
				<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|htmlsafe}" /></td>
			</div>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	{/if}
		
		<tr>
			<th>{$LANG.payment_type_method}</th>
			<td>
		
		{if $paymentTypes == null}
				<p><em>{$LANG.no_payment_types}</em></p>
		{else}
				<select name="ac_payment_type">
				{foreach from=$paymentTypes item=paymentType}
					<option value="{$paymentType.pt_id|htmlsafe}" {if $paymentType.pt_id == $defaults.payment_type}selected{/if}>
						{$paymentType.pt_description|htmlsafe}
					</option>
				{/foreach}
				</select>
		{/if}
			
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>{$LANG.note}</th>
			<td colspan="5"><textarea class="editor" name="ac_notes" rows="5" cols="50"></textarea></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
		<button type="submit" class="positive" name="process_payment" value="{$LANG.save}"><img class="button_img" src="./images/common/tick.png" alt="" />{$LANG.save}</button>        
		<a href="./index.php?module=payments&amp;view=manage" class="negative"><img src="./images/common/cross.png" alt="" />{$LANG.cancel}</a>
	</div>

    {if $smarty.get.op == 'pay_selected_invoice'}
        <input type="hidden" name="invoice_id" value="{$invoice.id|htmlsafe}" />
    {/if}
</div>
</form>
