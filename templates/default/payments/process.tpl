<form name="frmpost" action="index.php?module=payments&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">
<br /> 
<table align="center">	

{if $smarty.get.op === "pay_selected_invoice"}

<tr>
	<td class="details_screen">{$LANG.invoice_id}</td>
	<td><input type="hidden" name="ac_inv_id" value="{$invoice.id|escape:html}" />{$invoice.id|escape:html}</td>
	<td class="details_screen">{$LANG.total}</td>
	<td>{$invoice.total|number_format:2}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.biller}</td>
	<td>{$biller.name|escape:html}</td>
	<td class="details_screen">{$LANG.paid}</td>
	<td>{$invoice.paid|number_format:2}</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.customer}</td>
	<td>{$customer.name|escape:html}</td>
	<td class="details_screen">{$LANG.owing}</td>
	<td><u>{$invoice.owing|number_format:2}</u></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.amount}</td>
	<td colspan="5"><input type="text" name="ac_amount" size="25" value="{$invoice.owing|escape:html}" />
	<a class="cluetip" href="#"	rel="docs.php?t=help&amp;p=process_payment_auto_amount" title="{$LANG.process_payment_auto_amount}"><img src="./images/common/help-small.png" alt="" /></a>
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.date_formatted}</td>
	<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|escape:html}" /></td>
</tr>

{/if}


{if $smarty.get.op === "pay_invoice"}
	
<tr>
	<td class="details_screen">{$LANG.invoice_id}
	<a class="cluetip" href="#"	rel="docs.php?t=help&amp;p=process_payment_inv_id" title="{$LANG.process_payment_inv_id}"><img src="./images/common/help-small.png" alt="" /></a>
	</td>
	<td><input type="text" id="ac_me" name="ac_inv_id" /></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.details}
	<a class="cluetip" href="#"	rel="docs.php?t=help&amp;p=process_payment_details" title="{$LANG.process_payment_details}"><img src="./images/common/help-small.png" alt="" /></a>
	</td>
	<td id="js_total"><i>{$LANG.select_invoice}</i> </td>
</tr>
<tr>
	<td class="details_screen">{$LANG.amount}</td>
	<td colspan="5"><input type="text" name="ac_amount" size="25" /></td>
</tr>
<tr>
	<div class="demo-holder">
		<td class="details_screen">{$LANG.date_formatted}</td>
		<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|escape:html}" /></td>
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
			<option value="{$paymentType.pt_id|escape:html}" {if $paymentType.pt_id == $defaults.payment_type}selected{/if}>
				{$paymentType.pt_description|escape:html}
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

