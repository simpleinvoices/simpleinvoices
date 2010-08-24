{*
/*
* Script: add_invoice_item.tpl
* 	 Add new item to an existing invoice 
*
* Authors:
*	 Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{if $smarty.post.submit != null}
	<meta http-equiv="refresh" content="1;url=index.php?module=invoices&amp;view=details&amp;invoice={$smarty.post.invoice_id}&amp;type={$smarty.post.type}" />
	<br /><br />
	{$LANG.save_invoice_items_success};
	<br /><br />
{else}
<div id="top"><h3>{$LANG.add_invoice_item}</h3>
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
        	<img src="images/common/gmail-loader.gif" alt="Loading ..." /> Loading ...
</div>
</div>
 <hr />
<form name="add_invoice_item" action="index.php?module=invoices&amp;amp;view=add_invoice_item" method="post">
	<table align="center">
			<tr>
				<td class="details_screen">
					{$LANG.quantity}
				</td>
				<td>
					<input type="text" id="quantity1"  name="quantity1" size="5" />
				</td>
			</tr>
			<tr>
				<td class="details_screen">
					{$LANG.product}
				</td>
				<td><input type="text" name="description" />
				                
				{if $products == null }
					<p><em>{$LANG.no_products}</em></p>
				{else}

					<select
							class="product_select selector" 
							name="product1"
							onchange="
								invoice_product_change_price($(this).val(), 1, jQuery('#quantity1').val() );
								chain_select($(this).val(),'#attr1', 'attr1', 1,  jQuery('#quantity1').val() );
								chain_select($(this).val(),'#attr2', 'attr2', 1,  jQuery('#quantity1').val() );
								chain_select($(this).val(),'#attr3', 'attr3', 1,  jQuery('#quantity1').val() );
									"	
						>
					
						<option value=""></option>
					{foreach from=$products item=product}
						<option {if $product.id == $defaults.product} selected {/if} value="{$product.id}">{$product.description}</option>
					{/foreach}
					</select>
				{/if}
					                				                
                </td>
			</tr>
			<tr>
				<td class="details_screen">
					Attr. 1
				</td>
			<td>
				<select id="attr1" name="attr1" class="linkSel" disabled="disabled">
					<option value="">--  --</option>
				</select>
			</td>
			</tr>
			<tr>
				<td class="details_screen">
					Attr. 2
				</td>
			<td>
				<select id="attr2" name="attr2" class="linkSel" disabled="disabled">
					<option value="">--  --</option>
				</select>
			</td>
			</tr>	
			{if $number_of_attributes == "3"}
			<tr>
				<td class="details_screen">
					Attr. 3
				</td>
			<td>
				<select id="attr3" name="attr3" class="linkSel" disabled="disabled">
					<option value="">--  --</option>
				</select>
			</td>	
			</tr>	
			{/if}


			</tr>
			<tr>
				<td class="details_screen">
					{$LANG.unit_price}
				</td>
				<td>
					<input id="unit_price1" name="unit_price1" size="7" value="{$invoiceItem.unit_price|number_format:2}" />
				</td>
			</tr> 
			{if $type == 3}               
            	<tr>
					<td class="details_screen" colspan=2 >{$LANG.description}</td>
				</tr>
            	<tr>
					<td colspan="2"><textarea input type="text" name='description' rows="3" cols="80" wrap="nowrap"></textarea></td>
				</tr>
			{/if}
</table>
<hr />
<div style="text-align:center;">
	<input type="submit" name="submit" value="{$LANG.add_item}" />
	<input type="hidden" name="invoice_id" value="{$smarty.get.invoice}" />
	<input type="hidden" name="type" value="{$smarty.get.type}" />
	<input type="hidden" name="tax_id" value="{$smarty.get.tax_id}" />
</div>
</form>
{/if}
