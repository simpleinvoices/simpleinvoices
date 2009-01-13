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

<form name="frmpost" action="index.php?module=payment_types&amp;view=save&amp;id={$smarty.get.id|escape:html}" method="post" onsubmit="return frmpost_Validator(this)">




{if $smarty.get.action == "view" }
	
	
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.payment_type_id}</td><td>{$paymentType.pt_id|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description}</td><td>{$paymentType.pt_description|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td><td>{$paymentType.enabled|escape:html}</td>
	</tr>
	</table>
		<br>
	<table class="buttons" align="center">
		<tr>
			<td>
				<a href="./index.php?module=payment_types&view=details&id={$paymentType.pt_id}&action=edit" class="positive">
					<img src="./images/famfam/report_edit.png" alt=""/>
					{$LANG.edit}
				</a>

				<a href="./index.php?module=payment_types&view=manage" class="negative">
					<img src="./images/common/cross.png" alt=""/>
					{$LANG.cancel}
				</a>
		
			</td>
		</tr>
	 </table>
{/if}

{if $smarty.get.action == "edit"}

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.payment_type_id}</td>
		<td>{$paymentType.pt_id|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description} <a href="docs.php?t=help&amp;p=required_field" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png" alt="(required)"></img></a></td>
		<td><input type="text" name="pt_description" value="{$paymentType.pt_description|escape:html|regex_replace:"/[\\\]/":""}"
		 size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled} </td>
		<td>
		{*displayblock enabled*}
		<select name="pt_enabled">
			<option value="{$paymentType.pt_enabled|escape:html}" selected style="font-weight: bold">{$paymentType.enabled|escape:html}</option>
			<option value="1">{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
		</select>
		{*/displayblock enabled*}
		
		</td>
	</tr>
	</table>
	<br>
	<table class="buttons" align="center">
		<tr>
			<td>
				<button type="submit" class="positive" name="save_payment_type" value="{$LANG.save}">
					<img class="button_img" src="./images/common/tick.png" alt=""/> 
					{$LANG.save}
				</button>

				<input type="hidden" name="op" value="edit_payment_type">
			
				<a href="./index.php?module=preferences&view=manage" class="negative">
					<img src="./images/common/cross.png" alt=""/>
					{$LANG.cancel}
				</a>
		
			</td>
		</tr>
	 </table>

{/if}
