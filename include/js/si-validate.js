/**
 * Form validation — Bootstrap 5 / Tabler style.
 * Uses class="needs-validation" novalidate + required HTML attributes.
 * Features:
 *   - Tom Select wrapper validation sync
 *   - Tab-aware: switches to the tab containing the first invalid field
 */
(function () {
	'use strict';

	/**
	 * For required <select> elements hidden by Tom Select, manually apply
	 * is-invalid / is-valid on the .ts-wrapper and show/hide .invalid-feedback.
	 */
	function syncTomSelects(form) {
		form.querySelectorAll('select[required]').forEach(function (sel) {
			var wrapper = sel.nextElementSibling;
			if (!wrapper || !wrapper.classList.contains('ts-wrapper')) return;
			var isEmpty = !sel.value;
			wrapper.classList.toggle('is-invalid', isEmpty);
			wrapper.classList.toggle('is-valid', !isEmpty);
			// Bootstrap's sibling CSS can't reach through the ts-wrapper, so
			// manually show/hide the adjacent .invalid-feedback.
			var feedback = wrapper.nextElementSibling;
			if (feedback && feedback.classList.contains('invalid-feedback')) {
				feedback.style.display = isEmpty ? 'block' : 'none';
			}
		});
	}

	/**
	 * If the invalid element lives inside a Bootstrap tab-pane, switch to that
	 * tab so the user can see the error immediately.
	 */
	function switchToTab(el) {
		var pane = el.closest('.tab-pane');
		if (!pane || !pane.id) return;
		var tabLink = document.querySelector('a[data-bs-toggle="tab"][href="#' + pane.id + '"]');
		if (tabLink && window.bootstrap && window.bootstrap.Tab) {
			window.bootstrap.Tab.getOrCreateInstance(tabLink).show();
		}
	}

	function validateForm(form) {
		if (!form || !form.classList.contains('needs-validation')) return true;
		syncTomSelects(form);
		form.classList.add('was-validated');
		if (form.checkValidity()) return true;
		var first = form.querySelector(':invalid');
		if (first) {
			switchToTab(first);
			setTimeout(function () { if (first.focus) first.focus(); }, 80);
		}
		return false;
	}

	function init() {
		document.querySelectorAll('form.needs-validation').forEach(function (form) {
			form.addEventListener('submit', function (e) {
				if (!validateForm(form)) e.preventDefault();
			});
		});
	}

	// Expose as globals so any remaining inline onsubmit attributes keep working.
	window.frmpost_Validator = validateForm;
	window.checkForm = validateForm;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
