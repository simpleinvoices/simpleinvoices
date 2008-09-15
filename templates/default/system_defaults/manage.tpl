
<h3>{$LANG.system_preferences}</h3>
    <hr />

	
	<table align=center>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=biller'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_biller}</td><td>{$defaultBiller.name|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=customer'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_customer}</td><td>{$defaultCustomer.name|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_tax}</td><td>{$defaultTax.tax_description|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=preference_id'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_invoice_preference}</td><td>{$defaultPreference.pref_description|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=line_items'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_number_items}</td><td>{$defaults.line_items|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_inv_template'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_inv_template}</td><td>{$defaults.template|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_payment_type'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.default_payment_type}</td><td>{$defaultPaymentType.pt_description|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'>
		<a href='index.php?module=system_defaults&amp;view=edit&amp;submit=delete'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.delete}
		<a class="cluetip" href="#"	rel="docs.php?t=help&p=delete" title="{$LANG.help}"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
		{$defaultDelete|escape:html}
		</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=logging'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.logging} 
		<a class="cluetip" href="#"	rel="docs.php?t=help&p=logging" title="{$LANG.help}"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>{$defaultLogging|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=language'>{$LANG.edit}</a></td><td class='details_screen'>{$LANG.language}</td><td>{$defaultLanguage|escape:html}</td>
	</tr>
        </table>
        
