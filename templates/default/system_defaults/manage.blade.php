{{-- System defaults – Tabler-inspired settings layout --}}
<div class="page-header mb-4">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="page-title">{{ $LANG['system_preferences'] ?? 'System preferences' }}</h1>
			<p class="text-secondary mb-0">Default biller, customer, tax, invoice preference and other options used when creating invoices.</p>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		{{-- Invoice defaults --}}
		<div class="card mb-4">
			<div class="card-header">
				<h3 class="card-title">
					<i class="ti ti-file-invoice me-2"></i>{{ $LANG['invoice'] ?? 'Invoice' }} defaults
				</h3>
			</div>
			<div class="list-group list-group-flush">
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_biller'] ?? 'Default biller',
					'value' => $defaultBiller['name'] ?? '',
					'edit_param' => 'biller',
					'icon' => 'ti-building-store',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_customer'] ?? 'Default customer',
					'value' => $defaultCustomer['name'] ?? '',
					'edit_param' => 'customer',
					'icon' => 'ti-users',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_tax'] ?? 'Default tax',
					'value' => $defaultTax['tax_description'] ?? '',
					'edit_param' => 'tax',
					'icon' => 'ti-receipt-tax',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_invoice_preference'] ?? 'Default invoice preference',
					'value' => $defaultPreference['pref_description'] ?? '',
					'edit_param' => 'preference_id',
					'icon' => 'ti-file-text',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_number_items'] ?? 'Default number of line items',
					'value' => $defaults['line_items'] ?? '',
					'edit_param' => 'line_items',
					'icon' => 'ti-list-numbers',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_inv_template'] ?? 'Default invoice template',
					'value' => $defaults['template'] ?? '',
					'edit_param' => 'def_inv_template',
					'icon' => 'ti-template',
					'help_url' => 'index.php?module=documentation&view=view&page=help_default_invoice_template_text',
					'help_title' => $LANG['default_inv_template'] ?? '',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['default_payment_type'] ?? 'Default payment type',
					'value' => $defaultPaymentType['pt_description'] ?? '',
					'edit_param' => 'def_payment_type',
					'icon' => 'ti-credit-card',
				])
			</div>
		</div>

		{{-- Behaviour --}}
		<div class="card mb-4">
			<div class="card-header">
				<h3 class="card-title">
					<i class="ti ti-adjustments me-2"></i>Behaviour
				</h3>
			</div>
			<div class="list-group list-group-flush">
				@include('system_defaults.manage_row', [
					'label' => $LANG['delete'] ?? 'Delete',
					'value' => $defaultDelete ?? '',
					'edit_param' => 'delete',
					'icon' => 'ti-trash',
					'help_url' => 'index.php?module=documentation&view=view&page=help_delete',
					'help_title' => $LANG['delete'] ?? '',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['logging'] ?? 'Logging',
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
			<div class="card-header">
				<h3 class="card-title">
					<i class="ti ti-language me-2"></i>Localisation &amp; form
				</h3>
			</div>
			<div class="list-group list-group-flush">
				@include('system_defaults.manage_row', [
					'label' => $LANG['language'] ?? 'Language',
					'value' => $defaultLanguage ?? '',
					'edit_param' => 'language',
					'icon' => 'ti-language',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['number_of_taxes_per_line_item'] ?? 'Taxes per line item',
					'value' => $defaults['tax_per_line_item'] ?? '',
					'edit_param' => 'tax_per_line_item',
					'icon' => 'ti-receipt-tax',
				])
			</div>
		</div>

		{{-- Features --}}
		<div class="card mb-4">
			<div class="card-header">
				<h3 class="card-title">
					<i class="ti ti-toggle-right me-2"></i>Features
				</h3>
			</div>
			<div class="list-group list-group-flush">
				@include('system_defaults.manage_row', [
					'label' => $LANG['inventory'] ?? 'Inventory',
					'value' => $defaultInventory ?? '',
					'edit_param' => 'inventory',
					'icon' => 'ti-package',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['product_attributes'] ?? 'Product attributes',
					'value' => $defaultProductAttributes ?? '',
					'edit_param' => 'product_attributes',
					'icon' => 'ti-tags',
				])
				@include('system_defaults.manage_row', [
					'label' => $LANG['large_dataset'] ?? 'Large dataset',
					'value' => $defaultLargeDataset ?? '',
					'edit_param' => 'large_dataset',
					'icon' => 'ti-database',
				])
			</div>
		</div>
	</div>
</div>
