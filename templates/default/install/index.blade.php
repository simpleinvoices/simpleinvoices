@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_head')

<div class="card">
	<div class="card-status-top bg-primary"></div>
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-database me-2"></i>{{ $LANG['setup_database'] ?? 'Set up database' }}</h3>
	</div>
	<div class="card-body">
		<p class="text-secondary mb-4">{{ $LANG['install_intro'] ?? 'To install Simple Invoices please:' }}</p>
		<ol class="list list-unstyled mb-0">
			<li class="mb-2">1. {{ $LANG['install_step_db'] ?? 'Create a blank MySQL database preferably with UTF-8 collation' }}</li>
			<li class="mb-2">2. {{ $LANG['install_step_config'] ?? "Enter the correct database connection details in the config/config.php file" }}</li>
			<li class="mb-4">3. {{ $LANG['install_step_review'] ?? "Review the connection details below and if correct click the button to install the database and essential data." }}</li>
		</ol>
		<div class="table-responsive">
			<table class="table table-vcenter card-table table-bordered">
				<thead>
					<tr>
						<th>{{ $LANG['setting'] ?? 'Setting' }}</th>
						<th>{{ $LANG['value'] ?? 'Value' }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-secondary">{{ $LANG['host'] ?? 'Host' }}</td>
						<td>{{ $config->database->params->host ?? '' }}</td>
					</tr>
					<tr>
						<td class="text-secondary">{{ $LANG['database'] ?? 'Database' }}</td>
						<td>{{ $config->database->params->dbname ?? '' }}</td>
					</tr>
					<tr>
						<td class="text-secondary">{{ $LANG['username'] ?? 'Username' }}</td>
						<td>{{ $config->database->params->username ?? '' }}</td>
					</tr>
					<tr>
						<td class="text-secondary">{{ $LANG['password'] ?? 'Password' }}</td>
						<td>**********</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="btn-list mt-3">
			<a href="./index.php?module=install&amp;view=structure" class="btn btn-primary">
				<i class="ti ti-check me-1"></i>{{ $LANG['install_database_and_essential'] ?? 'Install database & essential data' }}
			</a>
		</div>
	</div>
</div>

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_foot')
