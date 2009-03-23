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
			<td class="details_screen">Display Name</td><td>{$product_attribute.display_name}</td>
        </tr>
		</table>
		<hr />

<a href="index.php?module=product_attribute&amp;view=details&amp;id={$product_attribute.id}&amp;action=edit">{$LANG.edit}</a>

{/if}

{if $smarty.get.action== 'edit' }

<b>Product Attribute</b>
	<hr />

        <table align="center">
                <tr>
                        <td class="details_screen">{$LANG.id}</td><td>{$product_attribute.id}</td>
                </tr>
                <tr>
                        <td class="details_screen">{$LANG.name}</td><td><input type="text" name="name" value="{$product_attribute.name}" size="50" /></td>
                </tr>
                <tr>
                        <td class="details_screen">Display Name</td><td><input type="text" name="display_name" value="{$product_attribute.display_name}" size="50" /></td>
                </tr>
                </table>
		<hr />

<input type="submit" name="save_product_attribute" value="{$LANG.save}" />
<input type="hidden" name="op" value="edit_product_attribute" />
{/if}
</form>
