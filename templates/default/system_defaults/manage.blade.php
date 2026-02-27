<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['system_preferences'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-vcenter card-table">
				<thead>
					<tr>
						<th>{{ $LANG['setting'] ?? 'Setting' }}</th>
						<th class="w-1"></th>
						<th>{{ $LANG['value'] ?? 'Value' }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ $LANG['default_biller'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=biller' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultBiller['name'] ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['default_customer'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=customer' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultCustomer['name'] ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['default_tax'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultTax['tax_description'] ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['default_invoice_preference'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=preference_id' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultPreference['pref_description'] ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['default_number_items'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=line_items' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaults['line_items'] ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['default_inv_template'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_default_invoice_template_text" title="{{ $LANG['default_inv_template'] ?? '' }}"><i class="ti ti-help ms-1"></i></a>
						</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_inv_template' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaults['template'] ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['default_payment_type'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_payment_type' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultPaymentType['pt_description'] ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['delete'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_delete" title="{{ $LANG['delete'] ?? '' }}"><i class="ti ti-help ms-1"></i></a>
						</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=delete' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultDelete ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['logging'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_logging" title="{{ $LANG['logging'] ?? '' }}"><i class="ti ti-help ms-1"></i></a>
						</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=logging' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultLogging ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['language'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=language' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultLanguage ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['number_of_taxes_per_line_item'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax_per_line_item' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaults['tax_per_line_item'] ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['inventory'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=inventory' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultInventory ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['product_attributes'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_attributes' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultProductAttributes ?? '' }}</td>
					</tr>
					<tr>
						<td>{{ $LANG['large_dataset'] ?? '' }}</td>
						<td><a href='index.php?module=system_defaults&amp;view=edit&amp;submit=large_dataset' class="btn btn-sm btn-icon" title="{{ $LANG['edit'] ?? '' }}"><i class="ti ti-edit"></i></a></td>
						<td>{{ $defaultLargeDataset ?? '' }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
