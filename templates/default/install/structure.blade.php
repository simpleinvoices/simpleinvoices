@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_head')

<div class="card">
	<div class="card-status-top bg-success"></div>
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-circle-check me-2"></i>{{ $LANG['setup_complete'] ?? 'Setup complete' }}</h3>
	</div>
	<div class="card-body">
		<p class="text-secondary mb-4">{{ $LANG['install_setup_done'] ?? 'The database and essential data have been installed. You can now start using Simple Invoices.' }}</p>
		<p class="text-secondary mb-4">{{ $LANG['install_sample_optional'] ?? 'Optionally, you can install sample data (demo invoices, customers, products) to explore the application.' }}</p>
		<div class="btn-list mt-3">
			<a href="./index.php" class="btn btn-primary">
				<i class="ti ti-rocket me-1"></i>{{ $LANG['start_using_simple_invoices'] ?? 'Start using Simple Invoices' }}
			</a>
			<a href="./index.php?module=install&amp;view=sample_data" class="btn btn-outline-secondary">
				<i class="ti ti-database-import me-1"></i>{{ $LANG['install_sample_data'] ?? 'Install sample data' }}
			</a>
		</div>
	</div>
</div>

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_foot')
