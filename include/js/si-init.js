(function () {
	function init() {
		// Tabs: enhanced by si-bootstrap.js (Bootstrap 5 nav-tabs)
		// Export dialog: Bootstrap modal
		document.querySelectorAll('.show-summary').forEach(function (el) { el.style.display = 'none'; });
		document.querySelectorAll('.biller').forEach(function (el) { el.style.display = 'none'; });
		document.querySelectorAll('.customer').forEach(function (el) { el.style.display = 'none'; });
		document.querySelectorAll('.consulting').forEach(function (el) { el.style.display = 'none'; });
		document.querySelectorAll('.itemised').forEach(function (el) { el.style.display = 'none'; });
		document.querySelectorAll('.note').forEach(function (el) { el.style.display = 'none'; });

		var dialogEl = document.getElementById('dialog');
		var invoiceDialogBtn = document.getElementById('invoice_dialog');
		if (dialogEl && invoiceDialogBtn && window.bootstrap && window.bootstrap.Modal) {
			dialogEl.classList.add('modal', 'fade');
			dialogEl.setAttribute('tabindex', '-1');
			invoiceDialogBtn.addEventListener('click', function (e) {
				e.preventDefault();
				var m = window.bootstrap.Modal.getOrCreateInstance(dialogEl);
				m.show();
			});
		} else if (dialogEl) {
			dialogEl.style.display = 'none';
		}
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
