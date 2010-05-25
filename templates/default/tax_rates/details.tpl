<form name="frmpost" action="index.php?module=tax_rates&amp;view=save&amp;id={$smarty.get.id|urlencode}"
 method="post" onsubmit="return frmpost_Validator(this)">


{if $smarty.get.action === 'view' }
<br />

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.description}</td><td>{$tax.tax_description|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.rate}
		<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign"
				title="{$LANG.tax_rate}"
		>
		<img src="./images/common/help-small.png" />
		</a>
	</td>
	<td>
		{$tax.tax_percentage|siLocal_number} {$tax.type|htmlsafe}
	</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td><td>{$tax.enabled|htmlsafe}</td>
	</tr>
	</table>
	<br />
	<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=tax_rates&amp;view=details&amp;id={$tax.tax_id|urlencode}&amp;action=edit" class="positive">
                <img src="./images/famfam/report_edit.png" alt="" />
                {$LANG.edit}
            </a>
    
        </td>
    </tr>
	</table>


{/if}

{if $smarty.get.action === 'edit'}



	<br />

	<table align="center">
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description}</td>
		<td><input type="text" name="tax_description" value="{$tax.tax_description|htmlsafe}"  class="validate[required]" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.rate}
		<a 
			class="cluetip"
			href="#"
			rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign"
			title="{$LANG.tax_rate}"
		>
			<img src="./images/common/help-small.png" />
		</a>
		</td>
		<td>
			<input type="text" name="tax_percentage" value="{$tax.tax_percentage|siLocal_number}" size="10" />
			{html_options name=type options=$types selected=$tax.type}
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled} </td><td>
		
		<select name="tax_enabled">
<option value="{$tax.tax_enabled|htmlsafe}" selected style="font-weight: bold">{$tax.enabled|htmlsafe}</option>
<option value="1">{$LANG.enabled}</option>
<option value="0">{$LANG.disabled}</option>
</select>

</td>
	</tr>
	</table>
    <br />
	<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="save_tax_rate" value="{$LANG.save_tax_rate}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>

			<input type="hidden" name="op" value="edit_tax_rate" />

            <a href="./index.php?module=tax_rates&view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
	</table>

{/if}


</form>

