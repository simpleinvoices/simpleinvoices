<form name="frmpost" action="index.php?module=payment_types&view=save" method="post" onsubmit="return frmpost_Validator(this)">
	<b>Payment type to add</b>
	<hr></hr>
	<table align=center>
		<tr>
			<td class="details_screen">Payment type description</td>
			<td><input type=text name="pt_description" size=50></td>
		</tr>
		<tr>
			<td class="details_screen">{$LANG.enabled}</td>
			<td>
				<select name="pt_enabled">
					<option value="1" selected>{$LANG.enabled}</option>
					<option value="0">{$LANG.disabled}</option>
				</select>
			</td>
		</tr>
	</table>
	<hr></hr>
	<input type=submit name="submit" value="{$LANG.insert_payment_type}">
	<input type=hidden name="op" value="insert_payment_type">
</form>
