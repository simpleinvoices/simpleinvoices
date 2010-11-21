{*
* Script: details.tpl
* 	Custom fields details template
*
* Website:
* 	 http://www.simpleinvoices.org
*
* License:
*	 GPL v3 or above
*}

<form name="frmpost" action="index.php?module=custom_fields&amp;view=save&amp;id={$smarty.get.id|urlencode}"
 method="POST" onsubmit="return frmpost_Validator(this);">


{if $smarty.get.action == "view" }

	<br />
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.id}</td>
		<td>{$cf.cf_id|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.custom_field_db_field_name}</td>
		<td>{$cf.cf_custom_field|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.custom_field}</td>
		<td>{$cf.name|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.custom_label}</td>
		<td>{$cf.cf_custom_label|htmlsafe}</td>
	</tr>
	</table>

<br />
<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=custom_fields&amp;view=details&amp;id={$cf.cf_id|urlencode}&amp;action=edit" class="positive">
                <img src="./images/common/tick.png" alt="" />
                {$LANG.edit}
            </a>
    
        </td>
    </tr>
</table>


{/if}

{if $smarty.get.action == "edit" }


	<br />

	<table align="center">
        <tr>
                <td class="details_screen">{$LANG.id}</td>
		<td>{$cf.cf_id|htmlsafe}</td>
        </tr>
        <tr>
                <td class="details_screen">{$LANG.custom_field_db_field_name}</td>
                <td>{$cf.cf_custom_field|htmlsafe}</td>
        </tr>
        <tr>
                <td class="details_screen">{$LANG.custom_field}</td>
                <td>{$cf.name|htmlsafe}</td>
        </tr>
	<tr>
		<td class="details_screen">{$LANG.custom_label}</td>
		<td><input type="text" name="cf_custom_label" size="25" value="{$cf.cf_custom_label|htmlsafe}" /></td>
	</tr>
	</table>
	<br />



<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="save_custom_field" value="{$LANG.save}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>

            <input type="hidden" name="op" value="edit_custom_field">
        
            <a href="./index.php?module=custom_fields&amp;view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
</table>
{/if}
</form>
