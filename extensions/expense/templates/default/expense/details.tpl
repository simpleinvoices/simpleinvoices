<form name="frmpost" action="index.php?module=products&view=save&id={$smarty.get.id}" method="post" id="frmpost" onSubmit="return checkForm(this);">


{if $smarty.get.action== 'view' }
<br />
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.product_description}</td>
		<td>{$product.description}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_unit_price}</td>
		<td>{$product.unit_price|siLocal_number}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.default_tax}</td>
		<td>
			{$tax_selected.tax_description} {$tax_selected.type}
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$product.custom_field1}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$product.custom_field2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$product.custom_field3}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$product.custom_field4}</td>
	</tr>
		{*
			{showCustomFields categorieId="3" itemId=$smarty.get.id } 
		*}
	<tr>
		<td class="details_screen">{$LANG.notes}</td><td>{$product.notes|unescape}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_enabled}</td>
		<td>{$product.wording_for_enabled}</td>
	</tr>
</table>
	<br />
	<table class="buttons" align="center">
		<tr>
			<td>
				<a href="./index.php?module=products&view=details&id={$product.id}&action=edit" class="positive">
					<img src="./images/famfam/add.png" alt=""/>
					{$LANG.edit}
				</a>

			</td>
		</tr>
	</table>
{/if}


{if $smarty.get.action== 'edit' }
<br />

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.amount}</td>
		<td>
		<input name="amount" class="validate[required]" value="{$expense.amount}" />
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.expense_account}</td>
		<td>
		<select name="expense_account_id" class="validate[required]">
		    <option value=''></option>
			{foreach from=$detail.expense_account_all item=expense_account}
				<option {if $expense_account.id == $expense.expense_account_id} selected {/if} value="{$expense_account.id}">{$expense_account.name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
    <tr wrap="nowrap">
            <td class="details_screen">{$LANG.date_formatted}</td>
            <td wrap="nowrap">
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date" value='{$expense.date}' />   
            </td>
    </tr>
	<tr>
		<td class="details_screen">{$LANG.biller}</td>
		<td>
		<select name="biller_id" class="validate[required]">
		    <option value=''></option>
			{foreach from=$detail.biller_all item=biller}
				<option {if $biller.id == $expense.biller_id} selected {/if} value="{$biller.id}">{$biller.name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer}</td>
		<td>
		<select name="customer_id">
		    <option value=''></option>
			{foreach from=$detail.customer_all item=customer}
				<option {if $biller.id == $expense.customer_id} selected {/if} value="{$customer.id}">{$customer.name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.invoice}</td>
		<td>
		<select name="invoice_id">
		    <option value=''></option>
			{foreach from=$detail.invoice_all item=invoice}
				<option  {if $invoice.id == $expense.invoice_id} selected {/if} value="{$invoice.id}">{$invoice.id}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product}</td>
		<td>
		<select name="product_id">
		    <option value=''></option>
			{foreach from=$detail.product_all item=product}
				<option {if $product.id == $expense.product_id} selected {/if} value="{$product.id}">{$product.description}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea input type="text" class="editor" name='note' rows="8" cols="50">{$expense.note|unescape}</textarea></td>
	</tr>
	</table>
{/if} 
{if $smarty.get.action== 'edit' }
	<br />
	<table class="buttons" align="center">
	<tr>
		<td>
			<button type="submit" class="positive" name="save_product" value="{$LANG.save}">
			    <img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<input type="hidden" name="op" value="edit_product">
		
			<a href="./index.php?module=products&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>
		
	{/if}
</form>
