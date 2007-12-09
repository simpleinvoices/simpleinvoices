{*
/*
* Script: details.tpl
* 	 Payment type details template
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

<form name="frmpost" action="index.php?module=payment_types&amp;view=save&amp;submit={$smarty.get.submit|escape:html}" method="post" onsubmit="return frmpost_Validator(this)">




{if $smarty.get.action == "view" }
	
	
	<b>{$LANG.payment_type} :: <a href='index.php?module=payment_types&amp;view=details&amp;submit={$paymentType.pt_id|escape:html}&amp;action=edit'>{$LANG.edit}</a> </b>
	<hr />

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
	<hr />

<a href='index.php?module=payment_types&amp;view=details&amp;submit={$paymentType.pt_id|escape:html}&amp;action=edit'>{$LANG.edit}</a>

{/if}

{if $smarty.get.action == "edit"}

	<b>{$LANG.payment_type_edit}</b>
	<hr />

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.payment_type_id}</td>
		<td>{$paymentType.pt_id|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.description} <a href="docs.php?t=help&amp;p=required_field" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png" alt="(required)"></img></a></td>
		<td><input type="text" name="pt_description" value="{$paymentType.pt_description|escape:html}"
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
	<hr />


<input type="submit" name="cancel" value="{$LANG.cancel}" />
<input type="submit" name="save_payment_type" value="{$LANG.save_payment_type}" />
<input type="hidden" name="op" value="edit_payment_type" />

{/if}
