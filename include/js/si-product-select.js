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

		var tsOptions = {
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
		};

		// When the add-product modal is present, wire up a "create" option so
		// typing a name that doesn't match shows "+ Add '<name>' as new product".
		var addProductModal = document.getElementById('modal-add-product');
		if (addProductModal) {
			tsOptions.create = function (input, callback) {
				window._siProductModalCallback = callback;
				var descEl = document.getElementById('si-new-product-description');
				if (descEl) descEl.value = input;
				tabler.Modal.getOrCreateInstance(addProductModal).show();
			};
			tsOptions.render = {
				option_create: function (data, escape) {
					return '<div class="create d-flex align-items-center gap-1"><i class="ti ti-plus"></i> Add <strong>' + escape(data.input) + '</strong> as new product</div>';
				}
			};
		}

		var ts = new TomSelect(selectEl, tsOptions);

		// Tom Select copies all classes from the original <select> to its wrapper
		// div, including any validation classes.  The wrapper has no .value
		// property, so si-validate.js treats it as always-empty and blocks form
		// submission.  Strip the validation class from the wrapper - the hidden
		// <select> (kept in sync by Tom Select) is what the validator should check.
		if (ts.wrapper) {
			ts.wrapper.classList.remove('validate[required]');
		}

		// When the select lives inside a Bootstrap input-group (so a "+ Add" button
		// sits flush against it), Tom Select's wrapper must flex like a form-control.
		// Bootstrap's input-group styles only target .form-control/.form-select as
		// direct children, so we patch the wrapper and its inner control manually.
		if (ts.wrapper && selectEl.closest('.input-group')) {
			ts.wrapper.style.flex     = '1 1 auto';
			ts.wrapper.style.width    = 'auto';
			ts.wrapper.style.minWidth = '0';
			var ctrl = ts.wrapper.querySelector('.ts-control');
			if (ctrl) {
				ctrl.style.borderTopRightRadius    = '0';
				ctrl.style.borderBottomRightRadius = '0';
			}
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
