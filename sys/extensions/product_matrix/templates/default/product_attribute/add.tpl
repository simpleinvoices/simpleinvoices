
{* if customer is updated or saved.*} 

{if $smarty.post.name != "" && $smarty.post.submit != null } 
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
		You must enter a name for the product attribute</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=product_attribute&amp;view=add" method="post">

<h3>Add product attribute</h3>

<hr />


<table align="center">
<tr>
	<td class="details_screen">Attribute name</td>
	<td><input type="text" name="name" value="{$smarty.post.name}" size="25" /></td>
</tr>
<tr>
	<td class="details_screen">Display name</td>
	<td><input type="text" name="display_name" value="{$smarty.post.display_name}" size="25" /></td>
</tr>
</table>

<hr />
<div style="text-align:center;">
	<input type="submit" name="submit" value="Insert Product Attribute" />
	<input type="hidden" name="op" value="insert_product_attribute" />
</div>
</form>
	
{/if}
