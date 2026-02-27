{{-- Auth login - uses header (Tabler layout) and has its own footer - full page-center layout --}}
@include('templates.default.header')

<div class="page-wrapper">
	<div class="page-body d-flex flex-column align-items-center justify-content-center min-vh-100">
		<div class="container container-tight py-4">
			<div class="text-center mb-4">
				<a href="." class="navbar-brand navbar-brand-autodark">
					<h1 class="h2 mb-0">{{ $LANG['simple_invoices'] ?? '' }}</h1>
				</a>
			</div>
			<form action="" method="post" id="frmLogin" name="frmLogin" class="card card-md">
				<input type="hidden" name="action" value="login" />
				<div class="card-body">
					<h2 class="card-title text-center mb-4">{{ $LANG['simple_invoices'] ?? 'Simple Invoices' }}</h2>
					<div class="mb-3">
						<label class="form-label">{{ $LANG['email'] ?? '' }}</label>
						<input name="user" type="text" class="form-control" title="user" value="" placeholder="{{ $LANG['email'] ?? 'Email' }}" autocomplete="username" autofocus />
					</div>
					<div class="mb-3">
						<label class="form-label">{{ $LANG['password'] ?? '' }}</label>
						<input name="pass" type="password" class="form-control" title="password" placeholder="{{ $LANG['password'] ?? 'Password' }}" autocomplete="current-password" />
					</div>
					@if($errorMessage)
					<div class="alert alert-danger">{{ outhtml($errorMessage ?? '') }}</div>
					@endif
					<div class="form-footer">
						<button type="submit" class="btn btn-primary w-100">{{ $LANG['login'] ?? '' }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<footer class="footer footer-transparent d-print-none">
		<div class="container-xl text-center text-muted">
			<a href="http://www.simpleinvoices.org">{{ $LANG['simple_invoices_powered_by'] ?? '' }}</a>
		</div>
	</footer>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
@verbatim
<script>
document.addEventListener('DOMContentLoaded', function() {
	var form = document.querySelector('#frmLogin');
	if (form) {
		form.style.opacity = '0';
		requestAnimationFrame(function() {
			form.style.transition = 'opacity 0.3s';
			form.style.opacity = '1';
		});
	}
	document.frmLogin && document.frmLogin.user && document.frmLogin.user.focus();
});
</script>
@endverbatim

</body>
</html>
