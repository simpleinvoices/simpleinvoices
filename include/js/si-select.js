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

		// Capture the server-rendered selected value BEFORE Tom Select initialises.
		// With allowEmptyOption: false on required selects, Tom Select may discard
		// a pre-selected value during setup — we restore it afterwards.
		var initialValue = el.value;

		var ts = new TomSelect(el, {
			create:           false,
			// Required fields: treat blank as placeholder (shows hint text, full height).
			// Optional fields: allow blank as a selectable empty value.
			allowEmptyOption: !el.hasAttribute('required') && hasBlank,
			placeholder:      el.getAttribute('placeholder') || '',
			maxOptions:       null,       // show all options (client-side filter)
			sortField:        [{ field: 'text', direction: 'asc' }],
		});

		// Tom Select copies all classes from the <select> to its wrapper div.
		// Strip validate[required] from the wrapper — si-validate.js should
		// check the hidden <select> (kept in sync by Tom Select), not the div.
		if (ts.wrapper) {
			ts.wrapper.classList.remove('validate[required]');
		}

		// Restore pre-selected value if Tom Select lost it during init (see above).
		if (initialValue && ts.getValue() !== initialValue) {
			ts.setValue(initialValue, true); // silent — don't fire onChange
		}

		// Clear is-invalid state (set by si-validate.js) when user picks a value.
		ts.on('change', function (value) {
			if (!ts.wrapper) return;
			if (value) {
				ts.wrapper.classList.remove('is-invalid');
				var feedback = ts.wrapper.nextElementSibling;
				if (feedback && feedback.classList.contains('invalid-feedback')) {
					feedback.style.display = '';
				}
			}
		});
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
