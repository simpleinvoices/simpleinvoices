@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_head')

@if($saved == true)
<div class="card">
	<div class="card-status-top bg-success"></div>
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-circle-check me-2"></i>{{ $LANG['sample_data_installed'] ?? 'Sample data installed' }}</h3>
	</div>
	<div class="card-body">
		<p class="text-secondary mb-4">{{ $LANG['sample_data_done'] ?? 'Sample data has been imported into Simple Invoices. You will be redirected in a few seconds, or click below to continue.' }}</p>
		<meta http-equiv="refresh" content="3;URL=index.php" />
		<div class="btn-list mt-3">
			<a href="./index.php" class="btn btn-primary">
				<i class="ti ti-rocket me-1"></i>{{ $LANG['start_using_simple_invoices'] ?? 'Start using Simple Invoices' }}
			</a>
		</div>
	</div>
</div>
@else
<div class="card">
	<div class="card-status-top bg-danger"></div>
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-alert-circle me-2"></i>{{ $LANG['sample_data_error'] ?? 'Sample data import failed' }}</h3>
	</div>
	<div class="card-body">
		<p class="text-secondary mb-4">{{ $LANG['sample_data_error_msg'] ?? 'Something went wrong. Sample data has NOT been imported into Simple Invoices.' }}</p>
		<div class="btn-list mt-3">
			<a href="./index.php" class="btn btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['back'] ?? 'Back' }}
			</a>
		</div>
	</div>
</div>
@endif

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_foot')
