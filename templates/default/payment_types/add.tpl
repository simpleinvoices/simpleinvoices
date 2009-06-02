{*
/*
* Script: add.tpl
* 	 Payment type add template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
<form name="frmpost" action="index.php?module=payment_types&amp;view=save" method="post">
<br />
	<table align="center">
		<tr>
			<td class="details_screen">Payment type description 
			<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
				title="{$LANG.Required_Field}"
			>
		<img src="./images/common/required-small.png" alt="" /></a>			
		</td>
			<td><input class="validate[required]" type="text" name="pt_description" size="30" /></td>
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
	<br />
	<table class="buttons" align="center">
		<tr>
			<td>
				<button type="submit" class="positive" name="insert_preference" value="{$LANG.save}">
					<img class="button_img" src="./images/common/tick.png" alt="" /> 
					{$LANG.save}
				</button>

				<input type="hidden" name="op" value="insert_preference" />
			
				<a href="./index.php?module=payment_types&amp;view=manage" class="negative">
					<img src="./images/common/cross.png" alt="" />
					{$LANG.cancel}
				</a>
		
			</td>
		</tr>
	 </table>
	<div style="text-align:center;">
		<input type="hidden" name="op" value="insert_payment_type" />
	</div>
</form>
