<form name="frmpost" action="index.php?module=products&amp;view=save&amp;id={$smarty.get.id}" method="post" id="frmpost" onSubmit="return checkForm(this);">


{if $smarty.get.action== 'view' }

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.product_description}</td>
		<td>{$product.description}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_unit_price}</td>
		<td>{$product.unit_price|number_format:2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.default_tax}</td>
		<td>
			{$tax_selected.tax_description} {$tax_selected.type}
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1} 
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
		<img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$product.custom_field1}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2} 
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
		<img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$product.custom_field2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3} 
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
		<img src="./images/common/help-small.png" alt=""></a>
		</td>
		<td>{$product.custom_field3}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4} 
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
		<img src="./images/common/help-small.png" alt="" /></a>
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
				<a href="./index.php?module=products&amp;view=details&amp;id={$product.id}&amp;action=edit" class="positive">
					<img src="./images/famfam/add.png" alt=""/>
					{$LANG.edit}
				</a>

			</td>
		</tr>
	</table>
{/if}


{if $smarty.get.action== 'edit' }

	<h3>{$LANG.product_edit}</h3>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.product_description}</td>
		<td><input type="text" name="description" size="50" value="{$product.description}" id="description" class="required" onblur="checkField(this);"/></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_unit_price}</td>
		<td><input type="text" name="unit_price" size="25" value="{$product.unit_price}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.default_tax}</td>
		<td>
		<select name="default_tax_id">
			{foreach from=$taxes item=tax}
				<option value="{$tax.tax_id}" {if $tax.tax_id == $product.default_tax_id}selected{/if}>{$tax.tax_description}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
		<img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>
		<select name="custom_field1">
				<option value=""></option>
			{foreach from=$product_group item=pg}
				<option value="{$pg.name}" {if $pg.name == $product.custom_field1}selected{/if}>{$pg.name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
		<img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="custom_field2" size="50" value="{$product.custom_field2}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
		<img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="custom_field3" size="50" value="{$product.custom_field3}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
		<img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="custom_field4" size="50" value="{$product.custom_field4}" /></td>
	</tr>
	{*	{showCustomFields categorieId="3" itemId=$smarty.get.id } *}
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea name="notes" class="editor" rows="8" cols="50">{$product.notes|unescape}</textarea></td>
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
	<br />
	<table class="buttons" align="center">
	<tr>
		<td>
			<button type="submit" class="positive" name="save_product" value="{$LANG.save}">
			    <img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<input type="hidden" name="op" value="edit_product">
		
			<a href="./index.php?module=products&amp;view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>
		
	{/if}
</form>
