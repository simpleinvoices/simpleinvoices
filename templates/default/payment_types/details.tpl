{*
/*
* Script: details.tpl
* 	 Payment type details template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<form name="frmpost" action="index.php?module=payment_types&amp;view=save&amp;id={$smarty.get.id|htmlsafe}" method="post" onsubmit="return frmpost_Validator(this)">

{if $smarty.get.action == "view" }
	
<div class="si_form si_form_view">	
	<table>
		<tr>
			<th>{$LANG.description}</th>
			<td>{$paymentType.pt_description|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.enabled}</th>
			<td>{$paymentType.enabled|htmlsafe}</td>
		</tr>
	</table>
</div>
	
	<div class="si_toolbar si_toolbar_form">
		<a href="./index.php?module=payment_types&amp;view=details&amp;id={$paymentType.pt_id}&amp;action=edit" class="positive">
			<img src="./images/famfam/report_edit.png" alt="" />
			{$LANG.edit}
		</a>

		<a href="./index.php?module=payment_types&amp;view=manage" class="negative">
			<img src="./images/common/cross.png" alt="" />
			{$LANG.cancel}
		</a>
	</div>

{/if}



{if $smarty.get.action == "edit"}

<div class="si_form">	
	<table>
		<tr>
			<td class="details_screen">{$LANG.description} <a href="index.php?module=documentation&amp;view=view&amp;page=help_required_field" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png" alt="(required)" /></a></td>
			<td>
				<input type="text"  class="validate[required]"  name="pt_description" value="{$paymentType.pt_description|htmlsafe|htmlsafe}" size="30" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.enabled} </th>
			<td>
			{*displayblock enabled*}
			<select name="pt_enabled">
				<option value="{$paymentType.pt_enabled|htmlsafe}" selected style="font-weight: bold">{$paymentType.enabled|htmlsafe}</option>
				<option value="1">{$LANG.enabled}</option>
				<option value="0">{$LANG.disabled}</option>
			</select>
			{*/displayblock enabled*}
			</td>
		</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
				<button type="submit" class="positive" name="save_payment_type" value="{$LANG.save}">
					<img class="button_img" src="./images/common/tick.png" alt="" /> 
					{$LANG.save}
				</button>

				<a href="./index.php?module=preferences&amp;view=manage" class="negative">
					<img src="./images/common/cross.png" alt="" />
					{$LANG.cancel}
				</a>
	</div>

</div>

<input type="hidden" name="op" value="edit_payment_type">
{/if}
</form>