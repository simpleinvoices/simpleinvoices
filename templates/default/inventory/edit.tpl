{if $saved == 'true' }

	<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
	<div class="si_message_ok">{$LANG.save_inventory_success}</div>

{/if}

{if $saved == 'false' }

	<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
	<div class="si_message_error">{$LANG.save_inventory_failure}</div>

{/if}

{if $saved ==false}
	{if $smarty.post.op == 'add' AND $smarty.post.product_id == ''} 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must select a product</div>
		<hr />
	{/if}


{* is this still needed ?*}
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..." /> {$LANG.loading} ...</div>


<form name="frmpost" action="index.php?module=inventory&view=edit&id={$inventory.id|urlencode}" method="POST" id="frmpost">


<div class="si_form">
	<table>
    <tr>
		 <td class="details_screen">{$LANG.date_upper}</td>
		<td>
			<input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date" value='{$inventory.date|htmlsafe}' />	
		</td>
    </tr>
	<tr>
		<td class="details_screen">{$LANG.product}</td>
		<td>
			<select name="product_id" class="validate[required] product_inventory_change">
			    <option value=''></option>
				{foreach from=$product_all item=product}
					<option value="{$product.id|htmlsafe}" {if $product.id == $inventory.product_id}selected{/if} >
	                    {$product.description|htmlsafe}
	                </option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.quantity}</td>
		<td>
		    <input name="quantity" size="10" class="validate[required]" value='{$inventory.quantity|siLocal_number_formatted}'>
        </td>
     </tr>
	<tr>
		<td class="details_screen">{$LANG.cost}</td>
		<td>
		    <input id="cost" name="cost" size="10" class="validate[required]" value='{$inventory.cost|siLocal_number_formatted}'>
        </td>
     </tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea  name="note"  class="editor" rows="8" cols="50">{$inventory.note|outhtml}</textarea></td>
	</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
			<button type="submit" class="positive" name="id" value="{$LANG.save}">
			    <img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>
		
			<a href="./index.php?module=inventory&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	</div>
</div>

<input type="hidden" name="op" value="edit" />
</form>
{/if}
