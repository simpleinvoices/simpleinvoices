@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_head')

<div class="card">
	<div class="card-status-top bg-success"></div>
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-circle-check me-2"></i>{{ $LANG['essential_data_installed'] ?? 'Essential data installed' }}</h3>
	</div>
	<div class="card-body">
		<p class="text-secondary mb-4">{{ $LANG['install_essential_done'] ?? 'The Simple Invoices essential data has been imported. You can now start using Simple Invoices.' }}</p>
		<div class="btn-list mt-3">
			<a href="./index.php" class="btn btn-primary">
				<i class="ti ti-rocket me-1"></i>{{ $LANG['start_using_simple_invoices'] ?? 'Start using Simple Invoices' }}
			</a>
		</div>
	</div>
</div>

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_foot')
