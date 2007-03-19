<?php

$block = <<<EOD
<FORM name="frmpost" ACTION="index.php?module=payment_types&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">
		
<b>Payment type to add</b>
 <hr></hr>

<table align=center>
	<tr>
		<td class="details_screen">Payment type description</td>
		<td><input type=text name="pt_description" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">$wording_for_enabledField</td><td>
			<select name="pt_enabled">
				<option value="1" selected>$wording_for_enabledField</option>
				<option value="0">$wording_for_disabledField</option>
			</select>
		</td>
	</tr>
	
</table>
	<hr></hr>
	<input type=submit name="submit" value="$LANG_insert_payment_type">
	<input type=hidden name="op" value="insert_payment_type">
</FORM>
EOD;
?>
