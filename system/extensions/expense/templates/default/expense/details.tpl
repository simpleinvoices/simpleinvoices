<form name="frmpost" action="index.php?module=expense&view=save&id={$smarty.get.id}" method="post">


{if $smarty.get.action== 'view' }
<br />
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.amount}</td>
		<td>{$expense.amount|siLocal_number}</td>
	</tr>
    <tr>
		<td class="details_screen">{$LANG.tax}</td>
            <td>				                				                
                {foreach from=$detail.expense_tax_grouped item=tax}
                    {$tax.tax_name}:
                    {$tax.tax_amount|siLocal_number}
                {/foreach}
            </td>
    </tr>
    <tr>
		<td class="details_screen">{$LANG.total}</td>
            <td>
                    {$detail.expense_tax_total|siLocal_number}				                				                
            </td>
    </tr>
	<tr>
		<td class="details_screen">{$LANG.expense_account}&nbsp; &nbsp;</td>
		<td>{$detail.expense_account.name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.date_upper}</td>
		<td>{$expense.date|siLocal_date}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.biller}</td>
		<td>{$detail.biller.name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer}</td>
		<td>{$detail.customer.name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.invoice}</td>
		<td>{$detail.invoice.index_name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product}</td>
		<td>{$detail.product.description}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.status}</td>
		<td>{$detail.status_wording}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
        <td>{$expense.note|unescape}</td>
	</tr>
</table>
	<br />
	<table class="buttons" align="center">
		<tr>
			<td>
				<a href="./index.php?module=expense&view=details&id={$expense.id}&action=edit" class="positive">
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
		<input name="amount" class="validate[required]" value="{$expense.amount|siLocal_number_trim}" />
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
				<option {if $customer.id == $expense.customer_id} selected {/if} value="{$customer.id}">{$customer.name}</option>
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
				<option  {if $invoice.id == $expense.invoice_id} selected {/if} value="{$invoice.id}">{$invoice.index_name}</option>
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
			<td class="details_screen">{$LANG.tax}</td>
                <td>
                    <table>     
                        <tr>
						{section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
							<td>				                				                
								<select 
									id="tax_id[0][{$smarty.section.tax.index}]"
									name="tax_id[0][{$smarty.section.tax.index}]"
								>
								<option value=""></option>
								{assign var="index" value=$smarty.section.tax.index}
								{foreach from=$taxes item=tax}
									<option {if $tax.tax_id === $detail.expense_tax.$index.tax_id} selected {/if} value="{$tax.tax_id}">{$tax.tax_description}</option>
								{/foreach}
							</select>
							</td>
						{/section}
                        </tr>
                    </table>
				<td>
        </tr>
         <tr>
             <td class="details_screen">{$LANG.status} 
             </td>
             <td>
                 {* enabled block *}
                 <select name="status">
                 <option value="{$expense.status}" selected
                 style="font-weight: bold;">{$detail.status_wording}</option>
                 <option value="1">{$LANG.paid}</option>
                 <option value="0">{$LANG.not_paid}</option>
                 </select>
                 {* /enabled block*}
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

			<input type="hidden" name="op" value="edit">
			<input type="hidden" name="id" value="{$smarty.get.id}">
		
			<a href="./index.php?module=expense&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>
		
	{/if}
</form>
