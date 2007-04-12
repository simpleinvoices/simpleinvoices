
<form name="frmpost" action="index.php?module=payment_types&view=save&submit={$smarty.get.submit}" method="post" onsubmit="return frmpost_Validator(this)">




{if $smarty.get.action == "view" }
	
	
	<b>{$LANG.payment_type}</b>
	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG['payment_type_id']}</td><td>{$paymentType.pt_id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['description']}</td><td>{$paymentType.pt_description}</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td><td>{$wording_for_enabled}</td>
	</tr>
	</table>
	<hr></hr>

<a href='index.php?module=payment_types&view=details&submit={$paymentType.pt_id}&action=edit'>{$LANG['edit']}</a>

{/if}

{if $smarty.get.action == "edit"}

	<b>{$LANG['payment_type_edit']}</b>
	<hr></hr>

	<table align=center>
	<tr>
		<td class="details_screen">{$LANG['payment_type_id']}</td>
		<td>{$paymentType.pt_id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description}</td>
		<td><input type="text" name="pt_description" value="{$paymentType.pt_description}"
		 size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField} </td>
		<td>
		{*displayblock enabled*}
		<select name="pt_enabled">
<option value="{$paymentType.pt_enabled}" selected style="font-weight: bold">{$wording_for_enabled}</option>
<option value="1">{$wording_for_enabledField}</option>
<option value="0">{$wording_for_disabledField}</option>
</select>
		{*/displayblock enabled*}
		
		</td>
	</tr>
	</table>
	<hr></hr>


<input type="submit" name="cancel" value="{$LANG.cancel}" />
<input type="submit" name="save_payment_type" value="{$LANG.save_payment_type}" />
<input type="hidden" name="op" value="edit_payment_type" />

{/if}
