@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_head')

@if(!empty($redirect_after_install))
	<meta http-equiv="refresh" content="0;URL={{ $redirect_after_install }}" />
	<script>
		window.location.replace(@json($redirect_after_install));
	</script>
@endif

<div class="card">
	<div class="card-status-top {{ !empty($redirect_after_install) ? 'bg-success' : 'bg-primary' }}"></div>
	<div class="card-header">
		<h3 class="card-title">
			<i class="ti {{ !empty($redirect_after_install) ? 'ti-circle-check' : 'ti-database' }} me-2"></i>
			{{ !empty($redirect_after_install) ? ($LANG['setup_complete'] ?? '') : ($LANG['setup_database'] ?? '') }}
		</h3>
	</div>
	<div class="card-body">
		@if(!empty($redirect_after_install))
			<p class="text-secondary mb-0">{{ $LANG['install_setup_done'] ?? '' }}</p>
		@else
		<p class="text-secondary mb-4">{{ $LANG['install_intro'] ?? '' }}</p>
		<ol class="list list-unstyled mb-0">
			<li class="mb-2">1. {{ $LANG['install_step_db'] ?? '' }}</li>
			<li class="mb-2">2. {{ $LANG['install_step_config'] ?? "Enter the correct database connection details in the config/config.php file" }}</li>
			<li class="mb-4">3. {{ $LANG['install_step_review'] ?? "Review the connection details below and if correct click the button to install the database and essential data." }}</li>
		</ol>
		@if(!empty($install_error))
			<div class="alert alert-danger mt-3 mb-0">
				{{ $LANG['sample_data_error_msg'] ?? '' }}
			</div>
		@endif
		<div class="table-responsive">
			<table class="table table-vcenter card-table table-bordered">
				<thead>
					<tr>
						<th>{{ $LANG['setting'] ?? '' }}</th>
						<th>{{ $LANG['value'] ?? '' }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-secondary">{{ $LANG['host'] ?? '' }}</td>
						<td>{{ $config->database->params->host ?? '' }}</td>
					</tr>
					<tr>
						<td class="text-secondary">{{ $LANG['database'] ?? '' }}</td>
						<td>{{ $config->database->params->dbname ?? '' }}</td>
					</tr>
					<tr>
						<td class="text-secondary">{{ $LANG['username'] ?? '' }}</td>
						<td>{{ $config->database->params->username ?? '' }}</td>
					</tr>
					<tr>
						<td class="text-secondary">{{ $LANG['password'] ?? '' }}</td>
						<td>**********</td>
					</tr>
				</tbody>
			</table>
		</div>
		<form method="post" action="./index.php?module=install&amp;view=index" class="mt-3">
			<input type="hidden" name="op" value="install_database" />
			<button type="submit" class="btn btn-primary">
				<i class="ti ti-check me-1"></i>{{ $LANG['install_database_and_essential'] ?? '' }}
			</button>
		</form>
		@endif
	</div>
</div>

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_foot')
