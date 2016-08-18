
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

<h3>{$LANG.add_product_attribute}</h3>

<hr />


<table align="center">
<tr>
	<td class="details_screen">{$LANG.name}</td>
	<td><input type="text" name="name" value="{$smarty.post.name}" size="25" /></td>
</tr>
		<tr>
			<th>{$LANG.type}</th>
			<td>
                <select name="type_id">
                    {foreach from=$types key=k item=v}
        				<option value="{$v.id}">{$LANG[$v.name]}</option>
                    {/foreach}
                </select>
			</td>
		</tr>
		<tr>
			<th>{$LANG.enabled}</th>
			<td>
				{html_options class=edit name=enabled options=$enabled selected=1}
			</td>
		</tr>
		<tr>
			<th>{$LANG.visible}</th>
			<td>
				{html_options class=edit name=visible options=$enabled selected=1}
			</td>
		</tr>
</table>

<hr />
<div style="text-align:center;">
	<input type="submit" name="submit" value="{$LANG.insert_product_attribute}" />
	<input type="hidden" name="op" value="insert_product_attribute" />
</div>
</form>
<script type="text/javascript" language="JavaScript">
document.forms['frmpost'].elements['name'].focus();
</script>
	
{/if}
