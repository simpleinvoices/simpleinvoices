@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_head')

@if(!empty($redirect_after_install))
	<meta http-equiv="refresh" content="0;URL={{ $redirect_after_install }}" />
	<script>
		window.location.replace(@json($redirect_after_install));
	</script>
@endif
@php
	$dbPort = trim((string)($config->database->params->port ?? ''));
	$dbHost = (string)($config->database->params->host ?? '');
	$dbAdapter = (string)($config->database->adapter ?? '');
@endphp

<div class="card shadow-sm border-0 w-100">
	<div class="card-status-top {{ !empty($redirect_after_install) ? 'bg-success' : 'bg-primary' }}"></div>
	<div class="card-header border-0 pb-0">
		<h1 class="card-title h3 mb-0">
			@if(!empty($redirect_after_install))
				<i class="ti ti-circle-check me-2 text-success"></i>{{ $LANG['setup_complete'] ?? '' }}
			@elseif(!empty($install_new_domain_bootstrap))
				<i class="ti ti-sparkles me-2"></i>{{ $LANG['install_new_domain_welcome_title'] ?? '' }}
			@else
				<i class="ti ti-rocket me-2"></i>{{ $LANG['setup_database'] ?? '' }}
			@endif
		</h1>
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
				@if(!empty($installLanguageList))
				<div class="mb-4 text-center">
					<label class="form-label fw-semibold">{{ $LANG['language'] ?? 'Language' }}</label>
					<select name="install_language" class="form-select mx-auto" style="max-width: 20rem;">
						@php
							$instLangDefault = (isset($_POST['install_language']) ? trim((string) $_POST['install_language']) : '') ?: ($installLanguageDefault ?? 'en_US');
						@endphp
						@foreach($installLanguageList as $lng)
						<option value="{{ $lng->shortname }}" @if($instLangDefault === (string) $lng->shortname) selected @endif>{{ $lng->name }} ({{ $lng->shortname }})</option>
						@endforeach
					</select>
				</div>
				@endif
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
			<p class="text-secondary lead fs-5 mb-4">{{ $LANG['install_intro'] ?? '' }}</p>

			<h2 class="h4 mb-3">{{ $LANG['install_section_prereq'] ?? 'Before you begin' }}</h2>
			<div class="row g-3 g-lg-4 mb-4">
				<div class="col-12 col-lg-4">
					<div class="card h-100 border border-secondary-lt">
						<div class="card-body p-3 p-md-4">
							<div class="d-flex align-items-start gap-3">
								<span class="badge bg-primary text-white rounded-pill flex-shrink-0" style="min-width:1.75rem;">1</span>
								<div class="min-w-0">
									<strong class="d-block mb-2 fs-5">{{ $LANG['install_step_db'] ?? '' }}</strong>
									<p class="text-secondary mb-0 small lh-base">{{ $LANG['install_step_1_sub'] ?? 'Create a database for your server.' }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-4">
					<div class="card h-100 border border-secondary-lt">
						<div class="card-body p-3 p-md-4">
							<div class="d-flex align-items-start gap-3">
								<span class="badge bg-primary text-white rounded-pill flex-shrink-0" style="min-width:1.75rem;">2</span>
								<div class="min-w-0">
									<strong class="d-block mb-2 fs-5">{{ $LANG['install_step_config'] ?? '' }}</strong>
									<p class="text-secondary mb-0 small lh-base">{{ $LANG['install_step_2_sub'] ?? '' }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-4">
					<div class="card h-100 border border-secondary-lt">
						<div class="card-body p-3 p-md-4">
							<div class="d-flex align-items-start gap-3">
								<span class="badge bg-primary text-white rounded-pill flex-shrink-0" style="min-width:1.75rem;">3</span>
								<div class="min-w-0">
									<strong class="d-block mb-2 fs-5">{{ $LANG['install_step_review'] ?? '' }}</strong>
									<p class="text-secondary mb-0 small lh-base">{{ $LANG['install_step_3_sub'] ?? '' }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row g-3 g-lg-4 mb-4">
				<div class="col-12 col-lg-6">
					<div class="card h-100 border-0 bg-azure-lt">
						<div class="card-body p-3 p-md-4">
							<div class="d-flex align-items-center gap-2 mb-2">
								<span class="avatar avatar-sm bg-azure-lt text-azure">
									<i class="ti ti-brand-docker"></i>
								</span>
								<h3 class="h5 mb-0">{{ $LANG['install_docker_title'] ?? 'Using Docker' }}</h3>
							</div>
							<p class="text-secondary small mb-0 lh-base">{{ $LANG['install_docker_help'] ?? '' }}</p>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-6">
					<div class="card h-100 border-0 bg-teal-lt">
						<div class="card-body p-3 p-md-4">
							<div class="d-flex align-items-center gap-2 mb-2">
								<span class="avatar avatar-sm bg-teal-lt text-teal">
									<i class="ti ti-file-code"></i>
								</span>
								<h3 class="h5 mb-0">{{ $LANG['install_php_title'] ?? 'Not using Docker' }}</h3>
							</div>
							<p class="text-secondary small mb-0 lh-base">{{ $LANG['install_php_config_help'] ?? '' }}</p>
						</div>
					</div>
				</div>
			</div>

			@if(!empty($install_error))
				<div class="alert alert-danger mb-4">{{ $LANG['sample_data_error_msg'] ?? '' }}</div>
			@endif

			<h2 class="h4 mb-3"><i class="ti ti-plug-connected me-1 text-secondary"></i>{{ $LANG['install_section_connection'] ?? 'Connection' }}</h2>
			<div class="table-responsive mb-4">
				<table class="table table-vcenter card-table table-bordered mb-0">
					<tbody>
						<tr>
							<td class="text-secondary" style="width:32%">{{ $LANG['install_connection_adapter'] ?? 'Adapter' }}</td>
							<td><code class="user-select-all">{{ $dbAdapter !== '' ? $dbAdapter : '—' }}</code></td>
						</tr>
						<tr>
							<td class="text-secondary">{{ $LANG['host'] ?? 'Host' }}</td>
							<td><code class="user-select-all">{{ $dbHost !== '' ? $dbHost : '—' }}</code></td>
						</tr>
						<tr>
							<td class="text-secondary">{{ $LANG['install_connection_port'] ?? 'Port' }}</td>
							<td><code class="user-select-all">{{ $dbPort !== '' ? $dbPort : '—' }}</code></td>
						</tr>
						<tr>
							<td class="text-secondary">{{ $LANG['database'] ?? 'Database' }}</td>
							<td><code class="user-select-all">{{ $config->database->params->dbname ?? '' }}</code></td>
						</tr>
						<tr>
							<td class="text-secondary">{{ $LANG['username'] ?? 'Username' }}</td>
							<td><code class="user-select-all">{{ $config->database->params->username ?? '' }}</code></td>
						</tr>
						<tr>
							<td class="text-secondary">{{ $LANG['password'] ?? 'Password' }}</td>
							<td><code>••••••••••</code></td>
						</tr>
					</tbody>
				</table>
			</div>

			<form method="post" action="./index.php?module=install&amp;view=index">
				<input type="hidden" name="op" value="install_database" />
				@if(!empty($installLanguageList))
				<div class="mb-4">
					<label class="form-label fw-semibold">{{ $LANG['language'] ?? 'Language' }}</label>
					<select name="install_language" class="form-select" style="max-width: 20rem;">
						@php
							$instLangDefault = (isset($_POST['install_language']) ? trim((string) $_POST['install_language']) : '') ?: ($installLanguageDefault ?? 'en_US');
						@endphp
						@foreach($installLanguageList as $lng)
						<option value="{{ $lng->shortname }}" @if($instLangDefault === (string) $lng->shortname) selected @endif>{{ $lng->name }} ({{ $lng->shortname }})</option>
						@endforeach
					</select>
					<p class="text-secondary small mt-1 mb-0">The currency and locale defaults on your new installation will match this choice.</p>
				</div>
				@endif
				<h2 class="h4 mb-2"><i class="ti ti-user-shield me-1 text-secondary"></i>{{ $LANG['install_section_admin'] ?? 'First administrator' }}</h2>
				<p class="text-secondary small mb-3">{{ $LANG['install_default_admin_blurb'] ?? '' }}</p>
				<div class="row g-3 mb-4">
					<div class="col-sm-6">
						<label class="form-label" for="install_admin_email">{{ $LANG['email'] ?? 'Email' }}</label>
						<input type="email" class="form-control" id="install_admin_email" name="install_admin_email"
							value="demo@simpleinvoices.org" required maxlength="255" autocomplete="username" />
					</div>
					<div class="col-sm-6">
						<label class="form-label" for="install_admin_password">{{ $LANG['password'] ?? 'Password' }}</label>
						<input type="password" class="form-control" id="install_admin_password" name="install_admin_password"
							value="demo" required minlength="4" maxlength="255" autocomplete="new-password" />
					</div>
				</div>
				<div class="d-flex flex-wrap align-items-center gap-2">
					<button type="submit" class="btn btn-primary btn-lg">
						<i class="ti ti-database-import me-1"></i>{{ $LANG['install_database_and_essential'] ?? 'Install Database' }}
					</button>
				</div>
			</form>
		@endif
	</div>
</div>

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.inc_foot')
