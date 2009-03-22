
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

<table>
	<tr>
		<td class="details_screen">{$LANG.product_description} </td>
		<td><input type="text" name="description" value="{$smarty.post.description}" size="20" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_unit_price}</td>
		<td><input type="text" name="unit_price" value="{$smarty.post.unit_price}"  size="10" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1}</td>
		<td><input type="text" name="custom_field1" value="{$smarty.post.custom_field1}"  size="20" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2}</td>
		<td><input type="text" name="custom_field2" value="{$smarty.post.custom_field2}" size="20" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3}</td>
		<td><input type="text" name="custom_field3" value="{$smarty.post.custom_field3}" size="20" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4}</td>
		<td><input type="text" name="custom_field4" value="{$smarty.post.custom_field4}" size="20" /></td>
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
