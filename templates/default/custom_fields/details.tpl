<FORM name="frmpost" ACTION="index.php?module=custom_fields&view=save&submit={$smarty.get.submit}"
 METHOD="POST" onsubmit="return frmpost_Validator(this)">


{if $smarty.get.action == "view" }

	<h3>{$LANG.custom_fields} ::
	<a href="index.php?module=custom_fields&view=details&submit={$cf.cf_id}&action=edit">{$LANG.edit}</a></h3>
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
	<hr></hr>


<a href="index.php?module=custom_fields&view=details&submit={$cf.cf_id}&action=edit">{$LANG.edit}</a>

{/if}

{if $smarty.get.action == "edit" }

	<b>{$LANG.custom_fields}</b>

	<hr></hr>

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
	<hr></hr>



<input type="submit" name="cancel" value="{$LANG.cancel}" />
<input type="submit" name="save_custom_field" value="{$LANG.save_custom_field}" />
<input type="hidden" name="op" value="edit_custom_field" />

{/if}



</form>
