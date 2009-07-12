
{* if bill is updated or saved. *}

{if $smarty.post.expense_account_id != "" && $smarty.post.id != null } 
	{include file="../extensions/expense/templates/default/expense/save.tpl"}
{else}
{* if  name was inserted *} 
	{if $smarty.post.id !=null} 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must enter a description for the product</div>
		<hr />
	{/if}


<form name="frmpost" action="index.php?module=expense&view=add" method="POST" id="frmpost">
<br />

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.expense_account}</td>
		<td>
		<select name="expense_account_id" class="validate[required]">
		    <option value=''></option>
			{foreach from=$expense_add.expense_account_all item=expense_account}
				<option value="{$expense_account.id}">{$expense_account.name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
    <tr wrap="nowrap">
            <td class="details_screen">{$LANG.date_formatted}</td>
            <td wrap="nowrap">
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date" value='{$smarty.now|date_format:"%Y-%m-%d"}' />   
            </td>
    </tr>
	<tr>
		<td class="details_screen">{$LANG.biller}</td>
		<td>
		<select name="biller_id" class="validate[required]">
		    <option value=''></option>
			{foreach from=$expense_add.biller_all item=biller}
				<option {if $biller.id == $defaults.biller} selected {/if} value="{$biller.id}">{$biller.name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer}</td>
		<td>
		<select name="customer_id" class="validate[required]">
		    <option value=''></option>
			{foreach from=$expense_add.customer_all item=customer}
				<option {if $biller.id == $defaults.customer} selected {/if} value="{$customer.id}">{$customer.name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.invoice}</td>
		<td>
		<select name="invoice_id" class="validate[required]">
		    <option value=''></option>
			{foreach from=$expense_add.invoice_all item=invoice}
				<option value="{$invoice.id}">{$invoice.id}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea input type="text" class="editor" name='notes' rows="8" cols="50">{$smarty.post.notes|unescape}</textarea></td>
	</tr>
</table>
<br />
<table class="buttons" align="center">
	<tr>
		<td>
			<button type="submit" class="positive" name="id" value="{$LANG.save}">
			    <img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<input type="hidden" name="op" value="expense_add" />
		
			<a href="./index.php?module=expense&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>


</form>
	{/if}
