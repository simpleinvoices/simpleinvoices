{{-- System defaults – Tabler-inspired settings layout --}}

<div class="row">
	<div class="col-12">
		{{-- Invoice defaults --}}
		<div class="card mb-4">
			<div class="list-group list-group-flush">
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_biller'] ?? '',
					'value' => $defaultBiller['name'] ?? '',
					'edit_param' => 'biller',
					'icon' => 'ti-building-store',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_customer'] ?? '',
					'value' => $defaultCustomer['name'] ?? '',
					'edit_param' => 'customer',
					'icon' => 'ti-users',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_tax'] ?? '',
					'value' => $defaultTax['tax_description'] ?? '',
					'edit_param' => 'tax',
					'icon' => 'ti-receipt-tax',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_invoice_preference'] ?? '',
					'value' => $defaultPreference['pref_description'] ?? '',
					'edit_param' => 'preference_id',
					'icon' => 'ti-file-text',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_number_items'] ?? '',
					'value' => $defaults['line_items'] ?? '',
					'edit_param' => 'line_items',
					'icon' => 'ti-list-numbers',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_inv_template'] ?? '',
					'value' => $defaults['template'] ?? '',
					'edit_param' => 'def_inv_template',
					'icon' => 'ti-template',
					'help_url' => 'index.php?module=documentation&view=view&page=help_default_invoice_template_text',
					'help_title' => $LANG['default_inv_template'] ?? '',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_export_template'] ?? '',
					'value' => $defaults['export_template'] ?? '',
					'edit_param' => 'def_export_template',
					'icon' => 'ti-file-export',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_payment_type'] ?? '',
					'value' => $defaultPaymentType['pt_description'] ?? '',
					'edit_param' => 'def_payment_type',
					'icon' => 'ti-credit-card',
				])
			</div>
		</div>

		{{-- Behaviour --}}
		<div class="card mb-4">
			<div class="list-group list-group-flush">
				@include('system_defaults.manage_row', [
					'label' => $LANG['delete'] ?? '',
					'value' => $defaultDelete ?? '',
					'edit_param' => 'delete',
					'icon' => 'ti-trash',
					'help_url' => 'index.php?module=documentation&view=view&page=help_delete',
					'help_title' => $LANG['delete'] ?? '',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['logging'] ?? '',
					'value' => $defaultLogging ?? '',
					'edit_param' => 'logging',
					'icon' => 'ti-file-description',
					'help_url' => 'index.php?module=documentation&view=view&page=help_logging',
					'help_title' => $LANG['logging'] ?? '',
				])
			</div>
		</div>

		{{-- Localisation & form --}}
		<div class="card mb-4">
			<div class="list-group list-group-flush">
				@include('system_defaults.manage_row', [
					'label' => $LANG['language'] ?? '',
					'value' => $defaultLanguage ?? '',
					'edit_param' => 'language',
					'icon' => 'ti-language',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['number_of_taxes_per_line_item'] ?? '',
					'value' => $defaults['tax_per_line_item'] ?? '',
					'edit_param' => 'tax_per_line_item',
					'icon' => 'ti-receipt-tax',
				])
			</div>
		</div>

		{{-- Features --}}
		<div class="card mb-4">
			<div class="list-group list-group-flush">
				@include('system_defaults.manage_row', [
					'label' => $LANG['inventory'] ?? '',
					'value' => $defaultInventory ?? '',
					'edit_param' => 'inventory',
					'icon' => 'ti-package',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['product_attributes'] ?? '',
					'value' => $defaultProductAttributes ?? '',
					'edit_param' => 'product_attributes',
					'icon' => 'ti-tags',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['large_dataset'] ?? '',
					'value' => $defaultLargeDataset ?? '',
					'edit_param' => 'large_dataset',
					'icon' => 'ti-database',
				])
			</div>
		</div>
	</div>
</div>
