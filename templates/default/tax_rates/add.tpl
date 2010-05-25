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
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must enter a Tax description</div>
		<hr />
	{/if}

<br />
<form name="frmpost" action="index.php?module=tax_rates&amp;view=add" method="POST">


<table align="center">
	<tr>
		<td class="details_screen">{$LANG.description}</td>
		<td><input type="text"  class="validate[required]" name="tax_description" value="{$smarty.post.tax_description|htmlsafe}" size="35" /></td>
		<td></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.rate}
		<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign"
				title="{$LANG.tax_rate}"
		>
		<img src="./images/common/help-small.png" alt="" />
		</a>
		</td>
		<td>
			<input type="text" name="tax_percentage" value="{$smarty.post.tax_percentage|htmlsafe}"  size="25" />
			{html_options name=type options=$types selected=$tax.type}
		</td>
		<td>{$LANG.ie_10_for_10}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			<select name="tax_enabled" value="{$smarty.post.tax_enabled|htmlsafe}">
			<option value="1" selected>{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
			</select>
		</td>
	</tr>
	
</table>
<br />
	<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="submit" value="{$LANG.insert_tax_rate}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>

			<input type="hidden" name="op" value="insert_tax_rate" />

            <a href="./index.php?module=tax_rates&view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
	</table>
</form>
{/if}
