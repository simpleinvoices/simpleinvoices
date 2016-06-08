<div class="si_form">
	<table>
		<tr>
			<th>{$LANG.default_biller}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=biller'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultBiller.name|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.default_customer}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=customer'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultCustomer.name|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.default_tax}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultTax.tax_description|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.default_invoice_preference}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=preference_id'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultPreference.pref_description|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.default_number_items}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=line_items'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaults.line_items|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.default_inv_template}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_default_invoice_template_text" title="{$LANG.default_inv_template}"><img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_inv_template'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaults.template|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.default_payment_type}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_payment_type'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultPaymentType.pt_description|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.delete}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_delete" title="{$LANG.delete}"><img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>
				<a href='index.php?module=system_defaults&amp;view=edit&amp;submit=delete'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultDelete|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.logging} 
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_logging" title="{$LANG.logging}"><img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=logging'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultLogging|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.language}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=language'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultLanguage|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.number_of_taxes_per_line_item}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax_per_line_item'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaults.tax_per_line_item|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.inventory}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=inventory'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultInventory|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.product_attributes}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_attributes'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultProductAttributes|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.large_dataset}</th>
			<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=large_dataset'><img src="images/common/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}" /></a></td>
			<td>{$defaultLargeDataset|htmlsafe}</td>
		</tr>
        {* This section will insert any extensions that add system-default fields *}
        {if $perform_extension_insertions}
          {section name=idx loop=$extension_insertion_files}
            {if $extension_insertion_files[idx].module  == 'system_defaults'}
              {include file=$extension_insertion_files[idx].file}
            {/if}
          {/section}
        {/if}

	</table>
</div> 
