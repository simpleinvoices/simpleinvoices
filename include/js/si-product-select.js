/**
 * Tom Select initialiser for product dropdowns (.product_change selects).
 *
 * Replaces the full server-rendered <option> list with an AJAX-backed
 * search widget so pages stay fast even with large product catalogs.
 *
 * Public API (used by add_line_item() in jquery_functions_js.blade.php):
 *   window.siInitProductSelect(selectEl)  – init or re-init one select
 */
(function () {
	'use strict';

	var SEARCH_URL = './index.php?module=invoices&view=product_search_ajax';

	function initProductSelect(selectEl) {
		if (!selectEl) return;
		// Already initialised (e.g. called twice by accident)
		if (selectEl.tomselect) return;

		// Large-dataset mode: only fetch when the field is focused (saves a
		// round-trip on page load when the catalog is huge).  Normal mode:
		// preload immediately so results are ready without any interaction.
		var isLargeDataset = (typeof si_conf_large_dataset !== 'undefined') && si_conf_large_dataset;

		var ts = new TomSelect(selectEl, {
			valueField:   'id',
			labelField:   'description',
			searchField:  ['description'],

			preload: isLargeDataset ? 'focus' : true,
			maxOptions: 10000,

			load: function (query, callback) {
				fetch(SEARCH_URL + '&q=' + encodeURIComponent(query))
					.then(function (r) { return r.json(); })
					.then(function (data) { callback(data); })
					.catch(function () { callback(); });
			},

			// Directly call the existing product-change handler instead of
			// relying on a native DOM change event (Tom Select may not fire one).
			onChange: function (value) {
				// Keep wrapper validation classes in sync after a failed submit.
				// Invoice forms skip was-validated and set is-invalid directly, so
				// check the wrapper itself rather than the form flag.
				if (ts.wrapper) {
					var isEmpty = !value;
					if (!isEmpty && ts.wrapper.classList.contains('is-invalid')) {
						ts.wrapper.classList.remove('is-invalid');
						var feedback = ts.wrapper.nextElementSibling;
						if (feedback && feedback.classList.contains('invalid-feedback')) {
							feedback.style.display = '';
						}
					}
					// Legacy was-validated path: also sync is-valid
					var form = selectEl.closest('form');
					if (form && form.classList.contains('was-validated')) {
						ts.wrapper.classList.toggle('is-invalid', isEmpty);
						ts.wrapper.classList.toggle('is-valid', !isEmpty);
					}
				}

				var row = selectEl.getAttribute('rel');
				var qEl = document.getElementById('quantity' + row);
				if (typeof invoice_product_change === 'function') {
					invoice_product_change(value, row, qEl ? qEl.value : '');
				}
			}
		});

		// Tom Select copies all classes from the original <select> to its wrapper
		// div, including any validation classes.  The wrapper has no .value
		// property, so si-validate.js treats it as always-empty and blocks form
		// submission.  Strip the validation class from the wrapper — the hidden
		// <select> (kept in sync by Tom Select) is what the validator should check.
		if (ts.wrapper) {
			ts.wrapper.classList.remove('validate[required]');
		}
	}

	function init() {
		document.querySelectorAll('select.product_change').forEach(function (el) {
			initProductSelect(el);
		});
	}

	// Export so add_line_item() can re-initialise on cloned rows
	window.siInitProductSelect = initProductSelect;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());
