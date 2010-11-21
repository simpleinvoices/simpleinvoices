<form name="frmpost"
	action="index.php?module=products&amp;view=save&amp;id={$smarty.get.id}"
	method="post">


{if $smarty.get.action== 'view' }

	<b>{$LANG.products} ::
	<a href="index.php?module=products&amp;view=details&amp;id={$product.id}&amp;action=edit">{$LANG.edit}</a></b>
	
 	<hr />

	<table >
	<tr>
		<td class="details_screen">{$LANG.product_id}</td>
		<td>{$product.id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_description}</td>
		<td>{$product.description}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_unit_price}</td>
		<td>{$product.unit_price|number_format:2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1}</td>
		<td>{$product.custom_field1}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2}</td>
		<td>{$product.custom_field2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3}</td>
		<td>{$product.custom_field3}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4}</a></td>
		<td>{$product.custom_field4}</td>
	</tr>
	<tr>
		<td class="details_screen">Attribute 1</td>
	<td>
            {foreach from=$attributes item=attribute}
                {if $matrix1.attribute_id == $attribute.id}{$attribute.name}{/if}
            {/foreach}
		</td>
	</tr>
	<tr>
		<td class="details_screen">Attribute 2</td>
		<td>
            {foreach from=$attributes item=attribute}
                {if $matrix2.attribute_id == $attribute.id}{$attribute.name}{/if}
            {/foreach}
		</td>
	</tr>
	<tr>
		<td class="details_screen">Attribute 3</td>
		<td>
            {foreach from=$attributes item=attribute}
                {if $matrix3.attribute_id == $attribute.id}{$attribute.name}{/if}
            {/foreach}
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_enabled}</td>
		<td>{$product.wording_for_enabled}</td>
	</tr>
	</table>

<hr />
<a href="index.php?module=products&view=details&id={$product.id}&action=edit">{$LANG.edit}</a>
{/if}


{if $smarty.get.action== 'edit' }

	<b>{$LANG.product_edit}</b>
	<hr />

	<table >
	<tr>
		<td class="details_screen">{$LANG.product_id}</td><td>{$product.id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_description}</td>
		<td><input type="text" name="description" size="25" value="{$product.description}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_unit_price}</td>
		<td><input type="text" name="unit_price" size="25" value="{$product.unit_price}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1}</td>
		<td><input type="text" name="custom_field1" size="25" value="{$product.custom_field1}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2}</td>
		<td><input type="text" name="custom_field2" size="25" value="{$product.custom_field2}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3}</td>
		<td><input type="text" name="custom_field3" size="25" value="{$product.custom_field3}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4}</td>
		<td><input type="text" name="custom_field4" size="25" value="{$product.custom_field4}" /></td>
	</tr>
		<tr>
		<td class="details_screen">Attribute 1</td>
		<td>
		     <select name="attribute_1">
		     <option value=""></option>
            {foreach from=$attributes item=attribute}
                <option {if $matrix1.attribute_id == $attribute.id} selected{/if}   value="{$attribute.id}">{$attribute.name}</option>
            {/foreach}
            </select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">Attribute 2</td>
		<td>
		     <select name="attribute_2">
		     <option value=""></option>
            {foreach from=$attributes item=attribute}
                <option {if $matrix2.attribute_id == $attribute.id} selected{/if}   value="{$attribute.id}">{$attribute.name}</option>
            {/foreach}
            </select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">Attribute 3</td>
		<td>
		     <select name="attribute_3">
		     <option value=""></option>
            {foreach from=$attributes item=attribute}
                <option {if $matrix3.attribute_id == $attribute.id} selected{/if}   value="{$attribute.id}">{$attribute.name}</option>
            {/foreach}
            </select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_enabled}</td>
		<td>
			{html_options name=enabled options=$enabled selected=$product.enabled}
		</td>
	</tr>
	</table>
{/if} 
{if $smarty.get.action== 'edit' }
	<hr />
		<input type="submit" name="cancel" value="{$LANG.cancel}" /> 
		<input type="submit" name="save_product" value="{$LANG.save_product}" /> 
		<input type="hidden" name="op" value="edit_product" /> 
	{/if}
</form>
