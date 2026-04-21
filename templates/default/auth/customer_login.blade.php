{{-- Customer portal login - domain-scoped; uses header (Tabler) like staff login --}}
@include('templates.default.header')
@php
	$appName = $config->app?->name ?? ($LANG['simple_invoices'] ?? '');
	$appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
	$portalOk = ($portalDomainId ?? 0) > 0 && ($portalDomainName ?? '') !== '';
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
			@if(!$portalOk)
			<div class="card card-md">
				<div class="card-body">
					<h2 class="card-title text-center mb-3">{{ $LANG['login'] ?? 'Log in' }}</h2>
					<div class="alert alert-warning mb-0">
						This customer login link is missing a valid organisation. Please use the link provided by your supplier.
					</div>
				</div>
			</div>
			@else
			<form action="" method="post" id="frmCustomerLogin" name="frmCustomerLogin" class="card card-md">
				<input type="hidden" name="action" value="customer_login" />
				<input type="hidden" name="portal_domain_id" value="{{ (int) ($portalDomainId ?? 0) }}" />
				<input type="hidden" name="csrfprotectionbysr" value="{{ $loginCsrfToken ?? '' }}" />
				<div class="card-body">
					<h2 class="card-title text-center mb-2">{{ $LANG['login'] ?? 'Log in' }}</h2>
					<p class="text-secondary text-center small mb-4">Organisation: <strong>{{ outhtml($portalDomainName ?? '') }}</strong></p>
					<div class="mb-3">
						<label class="form-label">{{ $LANG['email'] ?? '' }}</label>
						<input name="user" type="text" class="form-control" title="user" value="" placeholder="{{ $LANG['email'] ?? '' }}" autocomplete="username" autofocus />
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
				</div>
			</form>
			@endif
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
	var form = document.querySelector('#frmCustomerLogin');
	if (form) {
		form.style.opacity = '0';
		requestAnimationFrame(function() {
			form.style.transition = 'opacity 0.3s';
			form.style.opacity = '1';
		});
	}
	document.frmCustomerLogin && document.frmCustomerLogin.user && document.frmCustomerLogin.user.focus();
});
</script>
@endverbatim

</body>
</html>
