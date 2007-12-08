<form name="frmpost" action="index.php?module=system_defaults&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">

		<b>{$LANG.system_preferences}</b>
 <hr></hr>

<table align="center">

        <tr>
                <td><br></td>
        </tr>
        <tr>
        <td class="details_screen">{$description}</td><td>{$value}</td>
        </tr>
        <tr>
                <td><br></td>
        </tr>

</tr>
</tr>
</table>
<!-- </div> -->
	<input type="hidden" name="name" value="{$default}">
	<input type="submit" name="submit" value="{$LANG.save}">
	<input type="hidden" name="op" value="update_system_defaults">

</form>
