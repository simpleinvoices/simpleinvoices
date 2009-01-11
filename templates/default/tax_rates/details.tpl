
<form name="frmpost" action="index.php?module=tax_rates&amp;view=save&amp;id={$smarty.get.id|escape:html}"
 method="post" onsubmit="return frmpost_Validator(this)">


{if $smarty.get.action === 'view' }
        <b>{$LANG.tax_rate} ::
        <a href="index.php?module=tax_rates&amp;view=details&amp;id={$tax.tax_id|escape:html}&amp;action=edit">{$LANG.edit}</a></b>

	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.tax_rate_id}</td><td>{$tax.tax_id|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description}</td><td>{$tax.tax_description|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.rate} {* TODO - add html button here *}</td><td>{$tax.tax_percentage|siLocal_number} {$tax.type|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td><td>{$tax.enabled|escape:html}</td>
	</tr>
	</table>
	<hr></hr>


<a href='index.php?module=tax_rates&amp;view=details&amp;id={$tax.tax_id|escape:html}&amp;action=edit'>{$LANG.edit}</a>
{/if}

{if $smarty.get.action === 'edit'}



        <b>{$LANG.tax_rate}</b> 

	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.tax_rate_id}</td><td>{$tax.tax_id|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description}</td>
		<td><input type="text" name="tax_description" value="{$tax.tax_description|escape:html}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.rate}</td>
		<td>
			<input type="text" name="tax_percentage" value="{$tax.tax_percentage|siLocal_number}" size="10" />
			{html_options name=type options=$types selected=$tax.type}
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled} </td><td>
		
		<select name="tax_enabled">
<option value="{$tax.tax_enabled|escape:html}" selected style="font-weight: bold">{$tax.enabled|escape:html}</option>
<option value="1">{$LANG.enabled}</option>
<option value="0">{$LANG.disabled}</option>
</select>

</td>
	</tr>
	</table><br>
	<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="save_tax_rate" value="{$LANG.save_tax_rate}">
                <img class="button_img" src="./images/common/tick.png" alt=""/> 
                {$LANG.save}
            </button>

			<input type="hidden" name="op" value="edit_tax_rate" />

            <a href="./index.php?module=tax_rates&view=manage" class="negative">
                <img src="./images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
	</table>

{/if}


</form>

