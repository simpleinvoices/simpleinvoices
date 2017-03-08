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
		<div class="validation_alert"><img src="images/common/important.png" alt="" />
		You must enter a value</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=product_value&amp;view=add" method="post">

<h3>{$LANG.add_product_value}</h3>

<hr />


<table class="center">
<tr>
	<td class="details_screen">{$LANG.attribute}</td>
	<td>
            <select name="attribute_id">
            {foreach from=$product_attributes item=product_attribute}
                <option value="{$product_attribute.id}">{$product_attribute.name}</option>
            {/foreach}
            </select>
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.value}</td>
	<td><input type="text" name="value" value="{$smarty.post.value}" size="25" /></td>
</tr>
		<tr>
			<th>{$LANG.enabled}</th>
			<td>
				{html_options class=edit name=enabled options=$enabled selected=1}
			</td>
		</tr>
</table>
<!-- </div> -->
<hr />
<div style="text-align:center;">
	<input type="submit" name="submit" value="{$LANG.insert_product_value}" />
	<input type="hidden" name="op" value="insert_product_value" />
</div>
</form>

{/if}
