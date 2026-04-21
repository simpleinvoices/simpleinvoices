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
			@if(!empty($redirect_after_install))
				<i class="ti ti-circle-check me-2"></i>{{ $LANG['setup_complete'] ?? '' }}
			@elseif(!empty($install_new_domain_bootstrap))
				<i class="ti ti-hand-friend me-2"></i>{{ $LANG['install_new_domain_welcome_title'] ?? '' }}
			@else
				<i class="ti ti-database me-2"></i>{{ $LANG['setup_database'] ?? '' }}
			@endif
		</h3>
	</div>
	<div class="card-body">
		@if(!empty($redirect_after_install))
			<p class="text-secondary mb-0">{{ !empty($install_new_domain_bootstrap) ? ($LANG['install_new_domain_done'] ?? '') : ($LANG['install_setup_done'] ?? '') }}</p>
		@elseif(!empty($install_new_domain_bootstrap))
			<div class="text-center px-xl-4 pb-1">
				<span class="avatar avatar-xl bg-primary-lt text-primary mb-3"><i class="ti ti-sparkles fs-1"></i></span>
				<p class="text-secondary mb-4 mx-auto" style="max-width: 34rem;">{{ $LANG['install_new_domain_combined_intro'] ?? $LANG['install_new_domain_welcome_body'] ?? '' }}</p>
			</div>
			@if(!empty($install_error))
				<div class="alert alert-danger mb-4">
					{{ $LANG['sample_data_error_msg'] ?? '' }}
				</div>
			@endif
			<form method="post" action="./index.php?module=install&amp;view=index&amp;step=setup" class="mt-0">
				<input type="hidden" name="op" value="install_database" />
				<div class="d-grid gap-2 col-md-8 col-lg-6 mx-auto">
					<button type="submit" class="btn btn-primary btn-lg" autofocus>
						<i class="ti ti-check me-1"></i>{{ $LANG['install_new_domain_complete_setup'] ?? '' }}
					</button>
					@if(!empty($LANG['install_new_domain_then_wizard']))
						<p class="text-secondary text-center small mb-0">{{ $LANG['install_new_domain_then_wizard'] }}</p>
					@endif
				</div>
			</form>
		@else
		<p class="text-secondary mb-4">{{ $LANG['install_intro'] ?? '' }}</p>
		<ol class="list list-unstyled mb-0">
			<li class="mb-2">1. {{ $LANG['install_step_db'] ?? '' }}</li>
			<li class="mb-2">2. {{ $LANG['install_step_config'] ?? "Enter the correct database connection details in the config/config.php file" }}</li>
			<li class="mb-4">3. {{ $LANG['install_step_review'] ?? "Review the connection details below and if correct click the button to install the database and essential data." }}</li>
		</ol>
		<div class="alert alert-info mb-4 py-2 small">
			<i class="ti ti-brand-docker me-1"></i>
			<strong>Running via Docker?</strong>
			Set database details using environment variables in your <code>.env</code> file or <code>docker-compose.yml</code> instead of editing <code>config/config.php</code>:
			<code class="d-block mt-1">SI_DB_HOST &nbsp;SI_DB_NAME &nbsp;SI_DB_USER &nbsp;SI_DB_PASSWORD &nbsp;SI_DATABASE_ADAPTER</code>
		</div>
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
			<h4 class="mb-3">{{ $LANG['install_admin_login_title'] ?? 'Admin login details' }}</h4>
			<p class="text-secondary mb-3">{{ $LANG['install_admin_login_intro'] ?? 'Set the email and password for the default administrator account.' }}</p>
			<div class="alert alert-info mb-3 py-2">
				<i class="ti ti-info-circle me-1"></i>
				Default login: <strong>demo@simpleinvoices.org</strong> / <strong>demo</strong> - update these fields to change them before installing.
			</div>
			<div class="mb-3">
				<label class="form-label" for="install_admin_email">{{ $LANG['email'] ?? 'Email' }}</label>
				<input type="email" class="form-control" id="install_admin_email" name="install_admin_email"
					value="demo@simpleinvoices.org" required maxlength="255" />
			</div>
			<div class="mb-4">
				<label class="form-label" for="install_admin_password">{{ $LANG['password'] ?? 'Password' }}</label>
				<input type="password" class="form-control" id="install_admin_password" name="install_admin_password"
					value="demo" required minlength="4" maxlength="255" autocomplete="new-password" />
			</div>
			<button type="submit" class="btn btn-primary">
				<i class="ti ti-check me-1"></i>{{ $LANG['install_database_and_essential'] ?? '' }}
			</button>
		</form>
		@endif
	</div>
</div>

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_foot')
