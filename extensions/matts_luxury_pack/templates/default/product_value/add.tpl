{if $smarty.post.value != "" && $smarty.post.submit != null } 
{$refresh_total}

<br />
<br />
{$display_block} 
<br />
<br />

{else}
{* if  name was inserted *} 
	{if $smarty.post.submit !=null} 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must enter a value</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=product_value&amp;view=add" method="post">

<!--<h3>{$LANG.add_product_value}</h3>

<hr />-->

	<div class="si_form">
		<table class="center">
		<tr>
			<th class="details_screen">{$LANG.attribute}</th>
			<td>
					<select name="attribute_id">
					{foreach from=$product_attributes item=product_attribute}
						<option value="{$product_attribute.id}">{$product_attribute.name}</option>
					{/foreach}
					</select>
			</td>
		</tr>
		<tr>
			<th class="details_screen">{$LANG.value}</th>
			<td><input type="text" name="value" value="{$smarty.post.value}" size="25" /></td>
		</tr>
				<tr>
					<th>{$LANG.enabled}</th>
					<td>
						{html_options class=edit name=enabled options=$enabled selected=1}
					</td>
				</tr>
		</table>

		<div class="si_toolbar si_toolbar_form">
			<button type="submit" class="positive" name="submit" value="{$LANG.save}">
				<img class="button_img" src="./images/common/tick.png" alt="" />
				{$LANG.save}
			</button>
			<a href="./index.php?module=products&view=manage" class="negative">
				<img src="./images/common/cross.png" alt="" />
				{$LANG.cancel}
			</a>
		</div>
	</div>
<!--<hr />
<div style="text-align:center;">
	<input type="submit" name="submit" value="{$LANG.insert_product_value}" />
	<input type="hidden" name="op" value="insert_product_value" />
</div>-->
	<input type="hidden" name="op" value="insert_product_value" />
</form>

{/if}
