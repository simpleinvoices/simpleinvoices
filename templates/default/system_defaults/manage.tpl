<br />
	<table align="center">
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=biller'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.default_biller}</td><td>{$defaultBiller.name|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=customer'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.default_customer}</td><td>{$defaultCustomer.name|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.default_tax}</td><td>{$defaultTax.tax_description|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=preference_id'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.default_invoice_preference}</td><td>{$defaultPreference.pref_description|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=line_items'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.default_number_items}</td><td>{$defaults.line_items|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_inv_template'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.default_inv_template}</td><td>{$defaults.template|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_payment_type'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.default_payment_type}</td><td>{$defaultPaymentType.pt_description|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen">
		<a href='index.php?module=system_defaults&amp;view=edit&amp;submit=delete'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.delete}
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_delete" title="{$LANG.delete}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$defaultDelete|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=logging'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.logging} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_logging" title="{$LANG.logging}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$defaultLogging|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=language'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.language}</td><td>{$defaultLanguage|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax_per_line_item'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.number_of_taxes_per_line_item}</td><td>{$defaults.tax_per_line_item|escape:html}</td>
	</tr>
	<tr>
		<td class="details_screen"><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=inventory'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td><td class="details_screen">{$LANG.inventory} 
		</td>
		<td>{$defaultInventory|escape:html}</td>
	</tr>
        </table>
       <br /> 
