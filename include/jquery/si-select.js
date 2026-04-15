/**
 * Tom Select initialiser for biller, customer, preference and invoice dropdowns.
 *
 * Targets server-rendered <select> elements by name — no AJAX required, all
 * options are already in the DOM.  Provides search/filter and a consistent UI
 * that matches the product-line Tom Select widgets.
 */
(function () {
	'use strict';

	var SELECTORS = [
		'select[name="biller_id"]',
		'select[name="customer_id"]',
		'select[name="email_biller"]',
		'select[name="email_customer"]',
		'select[name="preference_id"]',
		'select[name="invoice_id"]',
	];

	function initSelect(el) {
		if (!el || el.tomselect) return;

		// Detect whether the first option is a blank placeholder
		var hasBlank = el.options.length > 0 && el.options[0].value === '';

		var ts = new TomSelect(el, {
			create:           false,
			allowEmptyOption: hasBlank,
			maxOptions:       null,       // show all options (client-side filter)
			sortField:        [{ field: 'text', direction: 'asc' }],
		});

		// Tom Select copies all classes from the <select> to its wrapper div.
		// Strip validate[required] from the wrapper — si-validate.js should
		// check the hidden <select> (kept in sync by Tom Select), not the div.
		if (ts.wrapper) {
			ts.wrapper.classList.remove('validate[required]');
		}
	}

	function init() {
		SELECTORS.forEach(function (selector) {
			document.querySelectorAll(selector).forEach(initSelect);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());
