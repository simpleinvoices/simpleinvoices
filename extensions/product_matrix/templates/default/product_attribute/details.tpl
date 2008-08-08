<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost"
	action="index.php?module=product_attribute&view=save&id={$smarty.get.id}"
	method="post">


{if $smarty.get.action== 'view' }
	<b>Product Attribute :: <a href='index.php?module=product_attribute&view=details&id={$product_attribute.id}&action=edit'>Edit</a></b>
	<hr></hr>

	
	<table align=center>
		<tr>
  			<td class='details_screen'>ID</td><td>{$product_attribute.id}</td>
                </tr>
		<tr>	
			<td class='details_screen'>Name</td><td>{$product_attribute.name}</td>
        </tr>
        <tr>	
			<td class='details_screen'>Dsiplay Name</td><td>{$product_attribute.display_name}</td>
        </tr>
		</table>
		<hr></hr>

<a href='index.php?module=product_attribute&view=details&id={$product_attribute.id}&action=edit'>Edit</a>

{/if}

{if $smarty.get.action== 'edit' }

<b>Product Attribute</b>
	<hr></hr>

        <table align=center>
                <tr>
                        <td class='details_screen'>ID</td><td>{$product_attribute.id}</td>
                </tr>
                <tr>
                        <td class='details_screen'>Name</td><td><input type=text name='name' value="{$product_attribute.name}" size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Display Name</td><td><input type=text name='display_name' value="{$product_attribute.display_name}" size=50></td>
                </tr>
                </table>
		<hr></hr>

<input type=submit name='save_product_attribute' value='{$LANG.save}'>
<input type=hidden name='op' value='edit_product_attribute'>
{/if}
</form>
