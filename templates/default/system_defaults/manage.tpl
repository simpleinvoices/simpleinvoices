
<h3>{$LANG.system_preferences}</h3>
    <hr />

	
	<table align=center>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=biller'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_biller}</td><td>{$defaultBiller.name}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=customer'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_customer}</td><td>{$defaultCustomer.name}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=tax'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_tax}</td><td>{$defaultTax.tax_description}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=preference_id'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_invoice_preference}</td><td>{$defaultPreference.pref_description}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=line_items'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_number_items}</td><td>{$defaults.line_items}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=def_inv_template'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_inv_template}</td><td>{$defaults.template}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=def_payment_type'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_payment_type}</td><td>{$defaultPaymentType.pt_description}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=delete'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.delete}<a href="docs.php?t=help&p=delete" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$defaultDelete}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=logging'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.logging} <a href="docs.php?t=help&p=logging" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td>{$defaultLogging}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&view=edit&submit=language'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.language}</td><td>{$defaultLanguage}</td>
	</tr>
        </table>
        
