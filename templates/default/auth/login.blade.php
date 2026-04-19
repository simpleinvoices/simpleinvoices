{{-- Auth login - uses header (Tabler layout) and has its own footer - full page-center layout --}}
@include('templates.default.header')
@php
	$appName = $config->app?->name ?? ($LANG['simple_invoices'] ?? '');
	$appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
	$registerAllowed = $registerAllowed ?? false;
	$registerTabActive = !empty($registerTabActive);
@endphp

<div class="page-wrapper">
	<div class="page-body d-flex flex-column align-items-center justify-content-center min-vh-100">
		<div class="container container-tight py-4">
			<div class="text-center mb-4">
				<a href="." class="navbar-brand navbar-brand-autodark">
					@if(!empty($config->app?->logo))
						<img src="{{ $config->app->logo }}" alt="{{ $appName }}" class="mb-2" style="max-height: 48px;" />
					@endif
					<h1 class="h2 mb-0">{{ $appName }}</h1>
				</a>
			</div>
			<div class="card card-md">
				<div class="card-body">
					<h2 class="card-title text-center mb-3">{{ $appName }}</h2>

					@if($registerAllowed)
					<ul class="nav nav-tabs nav-fill mb-4" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link @if(!$registerTabActive) active @endif" id="tab-login-link" data-bs-toggle="tab" data-bs-target="#tab-login-pane" type="button" role="tab" aria-controls="tab-login-pane" aria-selected="{{ $registerTabActive ? 'false' : 'true' }}">{{ $LANG['login'] ?? 'Log in' }}</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link @if($registerTabActive) active @endif" id="tab-register-link" data-bs-toggle="tab" data-bs-target="#tab-register-pane" type="button" role="tab" aria-controls="tab-register-pane" aria-selected="{{ $registerTabActive ? 'true' : 'false' }}">{{ $LANG['register'] ?? 'Register' }}</button>
						</li>
					</ul>
					@endif

					<div class="tab-content">
						<div class="tab-pane fade @if(!$registerAllowed || !$registerTabActive) show active @endif" id="tab-login-pane" role="tabpanel" aria-labelledby="tab-login-link" tabindex="0">
							<form action="" method="post" id="frmLogin" name="frmLogin">
								<input type="hidden" name="action" value="login" />
								<input type="hidden" name="csrfprotectionbysr" value="{{ $loginCsrfToken ?? '' }}" />
								<div class="mb-3">
									<label class="form-label">{{ $LANG['email'] ?? '' }}</label>
									<input name="user" type="text" class="form-control" title="user" value="" placeholder="{{ $LANG['email'] ?? '' }}" autocomplete="username" @if(!$registerAllowed || !$registerTabActive) autofocus @endif />
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['password'] ?? '' }}</label>
									<input name="pass" type="password" class="form-control" title="password" placeholder="{{ $LANG['password'] ?? '' }}" autocomplete="current-password" />
								</div>
								@if($errorMessage)
								<div class="alert alert-danger">{{ outhtml($errorMessage ?? '') }}</div>
								@endif
								<div class="form-footer">
									<button type="submit" class="btn btn-primary w-100">{{ $LANG['login'] ?? '' }}</button>
								</div>
							</form>
						</div>

						@if($registerAllowed)
						<div class="tab-pane fade @if($registerTabActive) show active @endif" id="tab-register-pane" role="tabpanel" aria-labelledby="tab-register-link" tabindex="0">
							<form action="" method="post" id="frmRegister" name="frmRegister" class="needs-validation" novalidate>
								<input type="hidden" name="action" value="register" />
								<input type="hidden" name="csrfprotectionbysr" value="{{ $registerCsrfToken ?? '' }}" />
								<p class="text-secondary small mb-3">Create a new organisation. You will be the domain administrator. The domain name is used in URLs and must contain only letters, numbers, hyphens and underscores.</p>
								<div class="mb-3">
									<label class="form-label">Domain name <span class="text-danger">*</span></label>
									<input name="domain_name" type="text" class="form-control" required autocomplete="off"
										pattern="[a-zA-Z0-9_\-]+"
										placeholder="e.g. acme-corp"
										value="{{ post('domain_name') }}"
										@if($registerTabActive) autofocus @endif
										oninput="this.value=this.value.replace(/[^a-zA-Z0-9_\-]/g,'')" />
									<div class="invalid-feedback">Only letters, numbers, hyphens and underscores are allowed.</div>
								</div>
								<div class="mb-3">
									<label class="form-label">Administrator email <span class="text-danger">*</span></label>
									<input name="admin_email" type="email" class="form-control" required autocomplete="email"
										placeholder="admin@example.com"
										value="{{ post('admin_email') }}" />
									<div class="invalid-feedback">A valid email is required.</div>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['password'] ?? 'Password' }} <span class="text-danger">*</span></label>
									<input name="admin_password" type="password" class="form-control" required autocomplete="new-password" minlength="4"
										placeholder="{{ $LANG['password'] ?? '' }}" />
									<div class="invalid-feedback">Password is required (at least 4 characters).</div>
								</div>
								@if($registerError)
								<div class="alert alert-danger">{{ outhtml($registerError ?? '') }}</div>
								@endif
								<div class="form-footer">
									<button type="submit" class="btn btn-primary w-100">{{ $LANG['register'] ?? 'Register' }}</button>
								</div>
							</form>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer footer-transparent d-print-none">
		<div class="container-xl text-center text-muted">
			<a href="{{ $appWebsite }}" target="_blank" rel="noopener">{{ $LANG['simple_invoices_powered_by'] ?? '' }}</a>
		</div>
	</footer>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
@verbatim
<script>
document.addEventListener('DOMContentLoaded', function() {
	var card = document.querySelector('.card-md');
	if (card) {
		card.style.opacity = '0';
		requestAnimationFrame(function() {
			card.style.transition = 'opacity 0.3s';
			card.style.opacity = '1';
		});
	}
	var regPane = document.getElementById('tab-register-pane');
	var loginPane = document.getElementById('tab-login-pane');
	if (regPane && regPane.classList.contains('active')) {
		var de = document.frmRegister && document.frmRegister.domain_name;
		de && de.focus();
	} else if (loginPane && loginPane.classList.contains('active')) {
		document.frmLogin && document.frmLogin.user && document.frmLogin.user.focus();
	}
	var regForm = document.getElementById('frmRegister');
	if (regForm) {
		regForm.addEventListener('submit', function(ev) {
			if (!regForm.checkValidity()) {
				ev.preventDefault();
				ev.stopPropagation();
			}
			regForm.classList.add('was-validated');
		}, false);
	}
});
</script>
@endverbatim

</body>
</html>
