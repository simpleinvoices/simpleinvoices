<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost"
	action="index.php?module=product_attribute&amp;view=save&amp;id={$smarty.get.id}"
	method="post">


{if $smarty.get.action== 'view' }
	<b>Product Attribute :: <a href="index.php?module=product_attribute&amp;view=details&amp;id={$product_attribute.id}&amp;action=edit">{$LANG.edit}</a></b>
	<hr />

	
	<table align="center">
		<tr>
  			<td class="details_screen">{$LANG.id}</td><td>{$product_attribute.id}</td>
                </tr>
		<tr>	
			<td class="details_screen">{$LANG.name}</td><td>{$product_attribute.name}</td>
        </tr>
		<tr>
			<th>{$LANG.type}</th>
			<td>{$product_attribute.type|capitalize|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.enabled}</th>
			<td>{$product_attribute.wording_for_enabled|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.visible}</th>
			<td>{$product_attribute.wording_for_visible|htmlsafe}</td>
		</tr>
		</table>
		<hr />

<a href="index.php?module=product_attribute&amp;view=details&amp;id={$product_attribute.id}&amp;action=edit">{$LANG.edit}</a>

{/if}

{if $smarty.get.action== 'edit' }

<b>{$LANG.product_attribute}</b>
	<hr />

        <table align="center">
                <tr>
                        <td class="details_screen">{$LANG.id}</td><td>{$product_attribute.id}</td>
                </tr>
                <tr>
                        <td class="details_screen">{$LANG.name}</td><td><input type="text" name="name" value="{$product_attribute.name}" size="50" /></td>
                </tr>
		<tr>
			<th>{$LANG.type}</th>
			<td>
                <select name="type_id">
                    {foreach from=$types key=k item=v}
        				<option value="{$v.id}" {if $product_attribute.type_id == $v.id} selected {/if}>{$LANG[$v.name]}</option>
                    {/foreach}
                </select>
			</td>
		</tr>
                <tr>
		<th>{$LANG.enabled}</th>
		<td>
			{html_options name=enabled options=$enabled selected=$product_attribute.enabled}
		</td>
                </tr>
                <tr>
		<th>{$LANG.visible}</th>
		<td>
			{html_options name=visible options=$enabled selected=$product_attribute.visible}
		</td>
                </tr>
                </table>
		<hr />

<div style="text-align:center;">
	<input type="submit" name="save_product_attribute" value="{$LANG.save}" />
	<input type="hidden" name="op" value="edit_product_attribute" />
</div>
{/if}
</form>
