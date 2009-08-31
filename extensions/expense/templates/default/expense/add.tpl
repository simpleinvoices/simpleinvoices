
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
		<td class="details_screen">{$LANG.amount}</td>
		<td>
		<input name="amount" class="validate[required]">
		</input>
		</td>
	</tr>
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
		<select name="customer_id">
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
		<select name="invoice_id">
		    <option value=''></option>
			{foreach from=$expense_add.invoice_all item=invoice}
				<option value="{$invoice.id}">{$invoice.index_name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product}</td>
		<td>
		<select name="product_id">
		    <option value=''></option>
			{foreach from=$expense_add.product_all item=product}
				<option value="{$product.id}">{$product.description}</option>
			{/foreach}
		</select>
		</td>
	</tr>
        {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
        <tr>
            <td class="details_screen">
              {$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax.index+1}{/if}
             </td>
           <td>
            <select 
                id="tax_id[0][{$smarty.section.tax.index}]"
                name="tax_id[0][{$smarty.section.tax.index}]"
            >
            <option value=""></option>
            {foreach from=$taxes item=tax}
                <option {if $tax.tax_id == $defaults.tax AND $smarty.section.tax.index == 0} selected {/if}   value="{$tax.tax_id}">{$tax.tax_description}</option>
            {/foreach}
        </select>
        </td>
        </tr>
        {/section}
</tr>

     <tr>
         <td class="details_screen">{$LANG.status}
         </td>
         <td>
             <select name="status">
             <option value="1" selected>{$LANG.paid}</option>
             <option value="0">{$LANG.not_paid}</option>
             </select>
         </td>
     </tr>



	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea input type="text" class="editor" name='note' rows="8" cols="50">{$smarty.post.notes|unescape}</textarea></td>
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

			<input type="hidden" name="op" value="add" />
		
			<a href="./index.php?module=expense&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>


</form>
	{/if}
