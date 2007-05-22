{* if tax rate is updated or saved.*} 

{if $smarty.post.tax_description != "" && $smarty.post.submit != null } 
{$refresh_total}

<br />
<br />
{$display_block} 
<br />
<br />

{else}
{* if  name was inserted *} 
	{if $smarty.post.submit !=null} 
		<div class="validation_alert"><img src="./images/common/important.png"</img>
		You must enter a Tax description</div>
		<hr />
	{/if}

<form name="frmpost" ACTION="index.php?module=tax_rates&view=add" METHOD="POST">

<h3>{$LANG.tax_rate_to_add}</h3>

 <hr />

<table align=center>
	<tr>
		<td class="details_screen">{$LANG.tax_description}</td>
		<td><input type=text name="tax_description" value="{$smarty.post.tax_description}" size=50></td><td></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.tax_percentage}</td>
		<td><input type=text name="tax_percentage" value="{$smarty.post.tax_percentage}"  size=25> %</td>
		<td>{$LANG.ie_10_for_10}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			<select name="tax_enabled" value="{$smarty.post.tax_enabled}">
			<option value="1" selected>{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
			</select>
		</td>
	</tr>
	
</table>
<hr />
<div style="text-align:center;">
	<input type=submit name="submit" value="{$LANG.insert_tax_rate}">
	<input type=hidden name="op" value="insert_tax_rate">
</div>
</form>
{/if}
