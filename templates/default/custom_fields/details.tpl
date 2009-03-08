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

<form name="frmpost" action="index.php?module=custom_fields&view=save&id={$smarty.get.id}"
 method="POST" onsubmit="return frmpost_Validator(this)">


{if $smarty.get.action == "view" }

	<h3>{$LANG.custom_fields} ::
	<a href="index.php?module=custom_fields&view=details&id={$cf.cf_id}&action=edit">{$LANG.edit}</a></h3>
	<hr />
	
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.id}</td><td>{$cf.cf_id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.custom_field_db_field_name}</td>
		<td>{$cf.cf_custom_field}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.custom_field}</td>
		<td>{$cf.name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.custom_label}</td>
		<td>{$cf.cf_custom_label}</td>
	</tr>
	</table>
	<hr />


<a href="index.php?module=custom_fields&view=details&id={$cf.cf_id}&action=edit">{$LANG.edit}</a>

{/if}

{if $smarty.get.action == "edit" }

	<b>{$LANG.custom_fields}</b>

	<hr />

	<table align="center">
        <tr>
                <td class="details_screen">{$LANG.id}</td><td>{$cf.cf_id}</td>
        </tr>
        <tr>
                <td class="details_screen">{$LANG.custom_field_db_field_name}</td>
                <td>{$cf.cf_custom_field}</td>
        </tr>
        <tr>
                <td class="details_screen">{$LANG.custom_field}</td>
                <td>{$cf.name}</td>
        </tr>
	<tr>
		<td class="details_screen">{$LANG.custom_label}</td>
		<td><input type="text" name="cf_custom_label" size="50" value="{$cf.cf_custom_label}" /></td>
	</tr>
	</table>
	<hr />

<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="save_custom_field" value="{$LANG.save}">
                <img class="button_img" src="./images/common/tick.png" alt=""/> 
                {$LANG.save}
            </button>

            <input type="hidden" name="op" value="edit_custom_field">
        
            <a href="./index.php?module=custom_fields&view=manage" class="negative">
                <img src="./images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </td>
    </tr>
</table>
{/if}
</form>
