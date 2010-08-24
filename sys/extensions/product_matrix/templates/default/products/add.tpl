
{* if bill is updated or saved.*}

{if $smarty.post.description != "" && $smarty.post.id != null } 
	{include file="../templates/default/products/save.tpl"}
{else}
{* if  name was inserted *} 
	{if $smarty.post.id !=null} 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must enter a description for the product</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=products&amp;view=add" method="post">

<div id="top"><h3>&nbsp;{$LANG.product_to_add}&nbsp;</h3></div>
 <hr />

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.product_description} <a href="index.php?module=documentation&amp;view=view&amp;page=help_required_field" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png" alt="" /></a></td>
		<td><input type="text" name="description" value="{$smarty.post.description}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_unit_price}</td>
		<td><input type="text" name="unit_price" value="{$smarty.post.unit_price}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1} <a href="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png" alt="" /></a></td>
		<td><input type="text" name="custom_field1" value="{$smarty.post.custom_field1}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2} <a href="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png" alt="" /></a></td>
		<td><input type="text" name="custom_field2" value="{$smarty.post.custom_field2}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3} <a href="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png" alt="" /></a></td>
		<td><input type="text" name="custom_field3" value="{$smarty.post.custom_field3}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4} <a href="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png" alt="" /></a></td>
		<td><input type="text" name="custom_field4" value="{$smarty.post.custom_field4}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">Attribute 1</td>
		<td>
		     <select name="attribute_1">
		     <option value=""></option>
            {foreach from=$attributes item=attribute}
                <option value="{$attribute.id}">{$attribute.name}</option>
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
                <option value="{$attribute.id}">{$attribute.name}</option>
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
                <option value="{$attribute.id}">{$attribute.name}</option>
            {/foreach}
            </select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea input type="text" name="notes" rows="8" cols="50">{$smarty.post.notes}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_enabled}</td>
		<td>
			{html_options name=enabled options=$enabled selected=1}
		</td>
	</tr>
		{showCustomFields categorieId="3" itemId=""}
</table>
<!-- </div> -->
<hr />
<div style="text-align:center;">
	<input type="submit" name="id" value="{$LANG.insert_product}" />
	<input type="hidden" name="op" value="insert_product" />
</div>
</form>
	{/if}
