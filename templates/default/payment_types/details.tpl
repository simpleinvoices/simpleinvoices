
<form name="frmpost" action="index.php?module=payment_types&view=save&submit={$smarty.get.submit}" method="post" onsubmit="return frmpost_Validator(this)">




{if $smarty.get.action == "view" }
	
	
	<b>{$LANG.payment_type} :: <a href='index.php?module=payment_types&view=details&submit={$paymentType.pt_id}&action=edit'>{$LANG.edit}</a> </b>
	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.payment_type_id}</td><td>{$paymentType.pt_id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description}</td><td>{$paymentType.pt_description}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td><td>{$paymentType.enabled}</td>
	</tr>
	</table>
	<hr></hr>

<a href='index.php?module=payment_types&view=details&submit={$paymentType.pt_id}&action=edit'>{$LANG.edit}</a>

{/if}

{if $smarty.get.action == "edit"}

	<b>{$LANG.payment_type_edit}</b>
	<hr></hr>

	<table align=center>
	<tr>
		<td class="details_screen">{$LANG.payment_type_id}</td>
		<td>{$paymentType.pt_id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description} <a href="docs.php?t=help&p=required_field" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png"></img></a></td>
		<td><input type="text" name="pt_description" value="{$paymentType.pt_description}"
		 size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled} </td>
		<td>
		{*displayblock enabled*}
		<select name="pt_enabled">
			<option value="{$paymentType.pt_enabled}" selected style="font-weight: bold">{$paymentType.enabled}</option>
			<option value="1">{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
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
