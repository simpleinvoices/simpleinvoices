<form name="frmpost"
	action="index.php?module=product_value&amp;view=save&amp;id={$smarty.get.id}"
	method="post">


{if $smarty.get.action== 'view' }
	<b>Product Values :: <a href="index.php?module=product_value&amp;view=details&amp;id={$product_value.id}&amp;action=edit">{$LANG.edit}</a></b>
	<hr />

	
	<table align="center">
		<tr>
  			<td class="details_screen">{$LANG.id}</td><td>{$product_value.id}</td>
                </tr>
		<tr>	
			<td class="details_screen">{$LANG.attribute}</td><td>{$product_attribute}</td>
		</tr>
		<tr>	
			<td class="details_screen">{$LANG.value}</td><td>{$product_value.value}</td>
		</tr>
		</table>
		<hr />

<a href="index.php?module=product_value&amp;view=details&amp;id={$product_value.id}&amp;action=edit">{$LANG.edit}</a>

{/if}

{if $smarty.get.action== 'edit' }

<b>Preferences</b>
	<hr />

	<table align="center">
		<tr>
			<td class="details_screen">{$LANG.id}</td><td>{$product_value.id}</td>
		</tr>
		<tr>
			<td  class="details_screen">{$LANG.attribute}</td>
			<td>
		            <select name="attribute_id">
			            {foreach from=$product_attributes item=product_attribute}
			                <option {if $product_attributes == $product_value.attribute_id}selected{/if}value="{$product_attribute.id}">{$product_attribute.name}</option>
			            {/foreach}
		            </select>
			</td>
		<tr>
			<td class="details_screen">Value</td><td><input type="text" name="value" value="{$product_value.value}" size="50" /></td>
		</tr>

	</table>
	<hr />

<input type="submit" name="save_product_value" value="{$LANG.save}" />
<input type="hidden" name="op" value="edit_product_value" />
{/if}
</form>
