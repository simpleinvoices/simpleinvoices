@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_head')

@if(!empty($redirect_after_install))
	<meta http-equiv="refresh" content="0;URL={{ $redirect_after_install }}" />
	<script>
		window.location.replace(@json($redirect_after_install));
	</script>
@endif

<div class="card card-lg">
	<div class="card-status-top {{ !empty($redirect_after_install) ? 'bg-success' : 'bg-primary' }}"></div>
	<div class="card-header">
		<h3 class="card-title">
			@if(!empty($redirect_after_install))
				<i class="ti ti-circle-check me-2"></i>{{ $LANG['setup_complete'] ?? '' }}
			@elseif(!empty($install_new_domain_bootstrap))
				<i class="ti ti-sparkles me-2"></i>{{ $LANG['install_new_domain_welcome_title'] ?? '' }}
			@else
				<i class="ti ti-rocket me-2"></i>{{ $LANG['setup_database'] ?? '' }}
			@endif
		</h3>
	</div>
	<div class="card-body">
		{{-- Success state --}}
		@if(!empty($redirect_after_install))
			<p class="text-secondary mb-0">{{ !empty($install_new_domain_bootstrap) ? ($LANG['install_new_domain_done'] ?? '') : ($LANG['install_setup_done'] ?? '') }}</p>

		{{-- New domain bootstrap --}}
		@elseif(!empty($install_new_domain_bootstrap))
			<div class="text-center px-xl-4 pb-1">
				<p class="text-secondary mb-4 mx-auto" style="max-width: 34rem;">{{ $LANG['install_new_domain_combined_intro'] ?? $LANG['install_new_domain_welcome_body'] ?? '' }}</p>
			</div>
			@if(!empty($install_error))
				<div class="alert alert-danger mb-4">{{ $LANG['sample_data_error_msg'] ?? '' }}</div>
			@endif
			<form method="post" action="./index.php?module=install&amp;view=index&amp;step=setup">
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

		{{-- Fresh install --}}
		@else
			<p class="text-secondary mb-4">{{ $LANG['install_intro'] ?? '' }}</p>

			{{-- Steps --}}
			<div class="row g-3 mb-4">
				<div class="col-md-4">
					<div class="d-flex align-items-start">
						<span class="badge bg-primary-lt text-primary rounded-pill me-2" style="min-width:1.75rem;">1</span>
						<div>
							<strong class="d-block">{{ $LANG['install_step_db'] ?? '' }}</strong>
							<small class="text-secondary">Ensure your database server is running</small>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="d-flex align-items-start">
						<span class="badge bg-primary-lt text-primary rounded-pill me-2" style="min-width:1.75rem;">2</span>
						<div>
							<strong class="d-block">{{ $LANG['install_step_config'] ?? 'Configure database' }}</strong>
							<small class="text-secondary">Enter connection details in <code>config/config.php</code></small>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="d-flex align-items-start">
						<span class="badge bg-primary-lt text-primary rounded-pill me-2" style="min-width:1.75rem;">3</span>
						<div>
							<strong class="d-block">{{ $LANG['install_step_review'] ?? 'Review &amp; install' }}</strong>
							<small class="text-secondary">Verify details below and click install</small>
						</div>
					</div>
				</div>
			</div>

			{{-- Docker note --}}
			<div class="alert alert-light border mb-4 py-2 small">
				<i class="ti ti-brand-docker me-1 text-secondary"></i>
				<strong class="text-secondary">Docker users:</strong>
				Set database details via environment variables (<code>SI_DB_HOST</code>, <code>SI_DB_NAME</code>, <code>SI_DB_USER</code>, <code>SI_DB_PASSWORD</code>, <code>SI_DATABASE_ADAPTER</code>) in your <code>.env</code> or <code>docker-compose.yml</code>.
			</div>

			@if(!empty($install_error))
				<div class="alert alert-danger mb-4">{{ $LANG['sample_data_error_msg'] ?? '' }}</div>
			@endif

			{{-- Database connection details --}}
			<h5 class="mb-3"><i class="ti ti-database me-1 text-secondary"></i>Database Connection</h5>
			<div class="table-responsive mb-4">
				<table class="table table-vcenter card-table table-bordered">
					<tbody>
						<tr>
							<td class="text-secondary" style="width:30%">Host</td>
							<td><code>{{ $config->database->params->host ?? '' }}</code></td>
						</tr>
						<tr>
							<td class="text-secondary">Database</td>
							<td><code>{{ $config->database->params->dbname ?? '' }}</code></td>
						</tr>
						<tr>
							<td class="text-secondary">Username</td>
							<td><code>{{ $config->database->params->username ?? '' }}</code></td>
						</tr>
						<tr>
							<td class="text-secondary">Password</td>
							<td><code>••••••••••</code></td>
						</tr>
					</tbody>
				</table>
			</div>

			{{-- Admin login form --}}
			<form method="post" action="./index.php?module=install&amp;view=index">
				<input type="hidden" name="op" value="install_database" />
				<h5 class="mb-3"><i class="ti ti-user-shield me-1 text-secondary"></i>Admin Account</h5>
				<p class="text-secondary mb-3">Set the login credentials for the default administrator.</p>
				<div class="row g-3 mb-4">
					<div class="col-sm-6">
						<label class="form-label" for="install_admin_email">Email</label>
						<input type="email" class="form-control" id="install_admin_email" name="install_admin_email"
							value="demo@simpleinvoices.org" required maxlength="255" />
					</div>
					<div class="col-sm-6">
						<label class="form-label" for="install_admin_password">Password</label>
						<input type="password" class="form-control" id="install_admin_password" name="install_admin_password"
							value="demo" required minlength="4" maxlength="255" autocomplete="new-password" />
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-lg">
					<i class="ti ti-database-import me-1"></i>{{ $LANG['install_database_and_essential'] ?? 'Install Database' }}
				</button>
			</form>
		@endif
	</div>
</div>

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_foot')
