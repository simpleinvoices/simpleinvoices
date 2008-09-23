{*
* Script: add.tpl
* 	Biller add template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above
*}

{* if bill is updated or saved.*}

{if $smarty.post.name != "" && $smarty.post.submit != null } 
	{include file="../templates/default/billers/save.tpl"}
{else}
{* if no biller name was inserted *}

{if $smarty.post.submit !=null}
<div class="validation_alert"><img src="./images/common/important.png"</img>
You must enter a Biller name</div>
<hr />
{/if}
<form name="frmpost" action="index.php?module=billers&view=add"
	method="post"><h3>{$LANG.biller_to_add}</h3>
<hr />

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.biller_name} 
		<a 
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=required_field"
				title="{$LANG.Required_Field}"
		>
		<img src="./images/common/required-small.png"></img>
		</a>	
		</td>
		<td><input type=text name="name" value="{$smarty.post.name}"
			size=25></td>
	</tr>
	<tr> 
		<td class="details_screen">{$LANG.street}</td>
		<td><input type=text name="street_address"
			value="{$smarty.post.street_address}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2} 
			<a
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=street2"
				title="{$LANG.street2}"
			> 
			<img
				src="./images/common/help-small.png">
			</img> 
			</a>
		</td>
		<td><input type=text name="street_address2"
			value="{$smarty.post.street_address2}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type=text name="city" value="{$smarty.post.city}"
			size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type=text name="state" value="{$smarty.post.state}"
			size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type=text name="zip_code"
			value="{$smarty.post.zip_code}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type=text name="country" value="{$smarty.post.country}"
			size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type=text name="phone" value="{$smarty.post.phone}"
			size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type=text name="mobile_phone"
			value="{$smarty.post.mobile_phone}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type=text name="fax" value="{$smarty.post.fax}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td><input type=text name="email" value="{$smarty.post.email}"
			size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf1}
			<a
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=custom_fields"
				title="{$LANG.Custom_Fields}"
			> 
			<img
			src="./images/common/help-small.png"></img> </a>
		</td>
		<td><input type=text name="custom_field1"
			value="{$smarty.post.custom_field1}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf2} 
			<a
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=custom_fields"
				title="{$LANG.Custom_Fields}"
			> 
			<img
			src="./images/common/help-small.png"></img> </a>
		</td>
		<td><input type=text name="custom_field2"
			value="{$smarty.post.custom_field2}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf3} 
			<a
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=custom_fields"
				title="{$LANG.Custom_Fields}"
			> 
			<img
			src="./images/common/help-small.png"></img> </a>
		</td>
		<td><input type=text name="custom_field3"
			value="{$smarty.post.custom_field3}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf4} 
			<a
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=custom_fields"
				title="{$LANG.Custom_Fields}"
			> 
			<img
			src="./images/common/help-small.png"></img> </a>

		</td>
		<td><input type=text name="custom_field4"
			value="{$smarty.post.custom_field4}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.logo_file} 
			<a
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=insert_biller_text"
				title="{$LANG.Logo_File}"
			> 
			<img
			src="./images/common/help-small.png"></img> </a>
			</td>
		<td>
			{html_options name=logo output=$files values=$files selected=$files[0] }
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.invoice_footer}</td>
		<td><textarea input type=text class="editor" name="footer"
			rows=4 cols=50>{$smarty.post.footer}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea  input type=text class="editor" name="notes"
			 rows=8 cols=50>{$smarty.post.notes|unescape}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			{html_options name=enabled options=$enabled selected=1}
		</td>
	</tr>
	{showCustomFields categorieId="1" itemId=""}
	</div>
	</div>
	</div>
	</tbody>
</table>
<hr />
<div style="text-align:center;">
	<input type="submit" name="submit" value="{$LANG.insert_biller}" />
	<input type="hidden" name="op" value="insert_biller" />
</div>
</form>
{/if}
