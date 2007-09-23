{*
/*
* Script: manage.tpl
* 	 System Preferences manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
<h3>{$LANG.system_prefs}/h3>
<hr />	
<table border="0" cellspacing="0" cellpadding="5" align="center">
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=biller"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">{$LANG.biller}</td>
		<td>{$defaultBiller.name}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=customer"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">{$LANG.customer}</td>
		<td>{$defaultCustomer.name}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=tax"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">{$LANG.tax}</td>
		<td>{$defaultTax.tax_description}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=preference_id"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">{$LANG.inv_pref}</td>
		<td>{$defaultPreference.pref_description}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=line_items"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td
		><td class="details_screen">{$LANG.default_number_items}</td>
		<td>{$defaults.line_items}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=def_inv_template"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">{$LANG.default_inv_template}</td>
		<td>{$defaults.template}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=def_payment_type"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">{$LANG.default_payment_type}</td>
		<td>{$defaultPaymentType.pt_description}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=delete"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">LANG_TODO: Delete stuff ?Add help here?</td><td>{$defaultDelete}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=logging"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">LANG_TODO: Logging ?Add help here?</td>
		<td>{$defaultLogging}</td>
	</tr>
	<tr>
		<td class="details_screen"><a title="{$LANG.edit}" href="index.php?module=system_defaults&view=edit&submit=language"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="details_screen">LANG_TODO: Language ?Add help here?</td>
		<td>{$defaultLanguage}</td>
	</tr>
</table>
        
