<form name="frmpost" action="index.php?module=payments&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">
<br /> 
<table align="center">	

{if $smarty.get.op === "pay_selected_invoice"}

<tr>
	<td class="details_screen">{$invoice.preference|htmlsafe}</td>
	<td><input type="hidden" name="invoice_id" value="{$invoice.id|htmlsafe}" />{$invoice.index_id|htmlsafe}</td>
	<td class="details_screen">{$LANG.total}</td>
	<td>{$invoice.total|number_format:2}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.biller}</td>
	<td>{$biller.name|htmlsafe}</td>
	<td class="details_screen">{$LANG.paid}</td>
	<td>{$invoice.paid|number_format:2}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.customer}</td>
	<td>{$customer.name|htmlsafe}</td>
	<td class="details_screen">{$LANG.owing}</td>
	<td><u>{$invoice.owing|number_format:2}</u></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.amount}</td>
	<td colspan="5"><input type="text" name="ac_amount" size="25" value="{$invoice.owing|htmlsafe}" />
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_auto_amount" title="{$LANG.process_payment_auto_amount}"><img src="./images/common/help-small.png" alt="" /></a>
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.date_formatted}</td>
	<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|htmlsafe}" /></td>
</tr>

{/if}


{if $smarty.get.op === "pay_invoice"}
	
<tr>
	<td class="details_screen">{$LANG.invoice}
	</td>
	<td>
<select name="invoice_id" class="validate[required]">
    <option value=''></option>
    {foreach from=$invoice_all item=invoice}
        <option value="{$invoice.id|htmlsafe}">{$invoice.index_name|htmlsafe} ({$invoice.biller|htmlsafe}, {$invoice.customer|htmlsafe}, {$LANG.total} {$invoice.invoice_total|siLocal_number} : {$LANG.owing} {$invoice.owing|siLocal_number})</option>
    {/foreach}
</select>

</tr>
<tr>
<tr>
	<td class="details_screen">{$LANG.amount}</td>
	<td colspan="5"><input type="text" name="ac_amount" size="25" /></td>
</tr>
<tr>
	<div class="demo-holder">
		<td class="details_screen">{$LANG.date_formatted}</td>
		<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|htmlsafe}" /></td>
	</div>
</tr>

{/if}


<tr>
	<td class="details_screen">{$LANG.payment_type_method}</td>
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
</tr>
<tr>
	<td class="details_screen">{$LANG.note}</td>
	<td colspan="5"><textarea class="editor" name="ac_notes" rows="5" cols="50"></textarea></td>
</tr>
</table>

<br />

<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="process_payment" value="{$LANG.save}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>

            <input type="hidden" name="op" value="edit_preference" />
        
            <a href="./index.php?module=payments&amp;view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
 </table>
 </form>

