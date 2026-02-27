$(document).ready(function(){
	// Tabs: enhanced by si-bootstrap.js (Bootstrap 5 nav-tabs)
	// Export dialog: Bootstrap modal, no need to .hide()
	$('.show-summary').hide();
	$('.biller').hide();
	$('.customer').hide();
	$('.consulting').hide();
	$('.itemised').hide();
	$('.note').hide();

	var dialogEl = document.getElementById('dialog');
	var invoiceDialogBtn = document.getElementById('invoice_dialog');
	if (dialogEl && invoiceDialogBtn && window.bootstrap && window.bootstrap.Modal) {
		dialogEl.classList.add('modal', 'fade');
		dialogEl.setAttribute('tabindex', '-1');
		invoiceDialogBtn.addEventListener('click', function(e) {
			e.preventDefault();
			var m = window.bootstrap.Modal.getOrCreateInstance(dialogEl);
			m.show();
		});
	} else if (dialogEl) dialogEl.style.display = 'none';
});
