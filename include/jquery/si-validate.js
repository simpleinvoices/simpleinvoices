/**
 * Minimal vanilla form validation (replaces jQuery validationEngine for basic rules).
 * Supports class="validate[required]" and validate[required,custom[date],length[0,10]].
 */
(function () {
	'use strict';

	function parseValidateClass(className) {
		if (!className || className.indexOf('validate') === -1) return null;
		var m = className.match(/validate\[([^\]]+)\]/);
		return m ? m[1].split(/[,\[\]]+/).filter(Boolean) : null;
	}

	function checkRequired(el) {
		var tag = (el.tagName || '').toLowerCase();
		var type = (el.type || '').toLowerCase();
		if (tag === 'input') {
			if (type === 'checkbox' || type === 'radio') {
				var name = el.name;
				if (!name) return !el.checked;
				var group = document.querySelectorAll('input[name="' + name.replace(/"/g, '\\"') + '"]:checked');
				return group.length === 0;
			}
			return (el.value || '').trim() === '';
		}
		if (tag === 'select-one' || tag === 'select-multiple') {
			return !el.value;
		}
		return (el.value || '').trim() === '';
	}

	function checkDate(el) {
		var v = (el.value || '').trim();
		if (!v) return true;
		return /^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/.test(v);
	}

	function validateElement(el) {
		var rules = parseValidateClass(el.className);
		if (!rules || !rules.length) return true;
		for (var i = 0; i < rules.length; i++) {
			if (rules[i] === 'required' && checkRequired(el)) return false;
			if (rules[i] === 'custom' && rules[i + 1] === 'date' && !checkDate(el)) return false;
		}
		return true;
	}

	/**
	 * Bootstrap 5 / Tabler validation for forms with class="needs-validation".
	 * Relies on HTML5 `required` attributes + form.checkValidity().
	 * Tom Select hides the native <select> so we manually reflect validity on
	 * the .ts-wrapper and its adjacent .invalid-feedback sibling.
	 */
	function validateBootstrap(form) {
		// Reflect Tom Select state before checkValidity() so wrapper classes
		// are correct when was-validated is added.
		form.querySelectorAll('select[required]').forEach(function (sel) {
			var wrapper = sel.nextElementSibling;
			if (!wrapper || !wrapper.classList.contains('ts-wrapper')) return;
			var isEmpty = !sel.value;
			wrapper.classList.toggle('is-invalid', isEmpty);
			wrapper.classList.toggle('is-valid', !isEmpty);
			// Manually show/hide the adjacent feedback div (Bootstrap's sibling
			// CSS can't reach through the ts-wrapper that Tom Select inserts).
			var feedback = wrapper.nextElementSibling;
			if (feedback && feedback.classList.contains('invalid-feedback')) {
				feedback.style.display = isEmpty ? 'block' : 'none';
			}
		});

		form.classList.add('was-validated');
		return form.checkValidity();
	}

	function validateForm(form) {
		// Bootstrap 5 path for forms that opt-in with needs-validation class
		if (form.classList && form.classList.contains('needs-validation')) {
			return validateBootstrap(form);
		}

		// Legacy validate[required] path
		var invalid = [];
		var els = form.querySelectorAll('[class*="validate"]');
		for (var i = 0; i < els.length; i++) {
			if (!validateElement(els[i])) invalid.push(els[i]);
		}
		if (invalid.length) {
			if (invalid[0].focus) invalid[0].focus();
			if (invalid[0].reportValidity) invalid[0].reportValidity();
			return false;
		}
		return true;
	}

	function init() {
		document.querySelectorAll('form').forEach(function (form) {
			form.addEventListener('submit', function (e) {
				if (!validateForm(form)) e.preventDefault();
			});
		});
	}

	// Expose as globals so legacy onsubmit attributes work.
	window.frmpost_Validator = validateForm;
	window.checkForm = validateForm;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
