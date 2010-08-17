<form name="frmpost" action="index.php?module=payments&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">
<h3>{$LANG.process_payment}</h3>
 <hr />
 
<table align="center">	

{if $smarty.get.op === "pay_selected_invoice"}

<tr>
	<td class="details_screen">{$LANG.invoice_id}</td>
	<td><input type="hidden" name="ac_inv_id" value="{$invoice.id|escape:html}" />{$invoice.id|escape:html}</td>
	<td class="details_screen">{$LANG.total}</td><td>{$invoice.total|number_format:2}</td>
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
	<td colspan="5"><input type="text" name="ac_amount" size="25" value="{$invoice.owing|escape:html}" /></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.date_upper}</td>
	<td><input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|escape:html}" /></td>
</tr>

{/if}


{if $smarty.get.op === "pay_invoice"}
	
<tr>
	<td class="details_screen">{$LANG.invoice_id}
	<a href="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_inv_id" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png" alt="({$LANG.help})" /></a></td>
	<td><input type="text" id="ac_me" name="ac_inv_id" /></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.details}
	<a href="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_details" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png" alt="({$LANG.help})" /></a></td>
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
<option selected value="{$defaults.payment_type|escape:html}" style="font-weight: bold">{$pt.pt_description|escape:html}</option>

	{foreach from=$paymentTypes item=paymentType}
		<option value="{$paymentType.pt_id|escape:html}">
		{$paymentType.pt_description|escape:html}</option>
	{/foreach}
{/if}
	
	</td>
</tr>
</table>


<hr />
<div style="text-align:center;">
	<input type="submit" name="process_payment" value="{$LANG.process_payment}">
</div>
</form>

