{* Auth login - uses header.tpl (Tabler layout) and has its own footer *}
{include file=$path|cat:'../header.tpl'}

<div class="page-wrapper">
	<div class="page-body">
		<div class="container-xl">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
<form action="" method="post" id="frmLogin" name="frmLogin" class="card card-md">
	<input type="hidden" name="action" value="login" />
	<div class="card-body">
		<h1 class="card-title text-center mb-4">{$LANG.simple_invoices}</h1>
		<div class="mb-3">
			<label class="form-label">{$LANG.email}</label>
			<input name="user" type="text" class="form-control" title="user" value="" autocomplete="username" />
		</div>
		<div class="mb-3">
			<label class="form-label">{$LANG.password}</label>
			<input name="pass" type="password" class="form-control" title="password" autocomplete="current-password" />
		</div>
		{if $errorMessage}
		<div class="alert alert-danger">{$errorMessage|outhtml}</div>
		{/if}
		<div class="form-footer">
			<button type="submit" class="btn btn-primary w-100">{$LANG.login}</button>
		</div>
	</div>
</form>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer footer-transparent d-print-none">
		<div class="container-xl text-center text-muted">
			<a href="http://www.simpleinvoices.org">{$LANG.simple_invoices_powered_by}</a>
		</div>
	</footer>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
{literal}
<script>
document.addEventListener('DOMContentLoaded', function() {
	var box = document.querySelector('.card-md');
	if (box) { box.style.opacity = '0'; requestAnimationFrame(function() { box.style.transition = 'opacity 0.3s'; box.style.opacity = '1'; }); }
	document.frmLogin && document.frmLogin.user && document.frmLogin.user.focus();
});
</script>
{/literal}

</body>
</html>
