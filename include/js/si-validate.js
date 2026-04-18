/**
 * Form validation — Bootstrap 5 / Tabler style.
 * Uses class="needs-validation" novalidate + required HTML attributes.
 * Features:
 *   - Tom Select wrapper validation sync
 *   - Tab-aware: switches to the tab containing the first invalid field
 *   - Invoice line items: always validates row 0; validates rows 1+ only if
 *     they have a product or quantity entered (partial rows)
 *   - Invoice forms: only marks invalid fields red — no green on valid fields
 */
(function () {
	'use strict';

	/**
	 * For required <select> elements hidden by Tom Select, manually apply
	 * is-invalid / is-valid on the .ts-wrapper and show/hide .invalid-feedback.
	 * Used by non-invoice forms only.
	 */
	function syncTomSelects(form) {
		form.querySelectorAll('select[required]').forEach(function (sel) {
			var wrapper = sel.nextElementSibling;
			if (!wrapper || !wrapper.classList.contains('ts-wrapper')) return;
			var isEmpty = !sel.value;
			wrapper.classList.toggle('is-invalid', isEmpty);
			wrapper.classList.toggle('is-valid', !isEmpty);
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

	/**
	 * Apply or clear is-invalid on a field.
	 * For Tom Select selects, targets the .ts-wrapper (inserted as the next
	 * sibling of the hidden <select>) so the red border appears on the visible
	 * widget.  Also shows/hides any adjacent .invalid-feedback element.
	 */
	function setInvalid(el, invalid) {
		if (!el) return;
		var target = el;
		if (el.tagName === 'SELECT') {
			// Tom Select inserts .ts-wrapper as the next sibling of the hidden <select>
			var next = el.nextElementSibling;
			if (next && next.classList.contains('ts-wrapper')) target = next;
		}
		target.classList.toggle('is-invalid', invalid);
		// Show/hide the adjacent .invalid-feedback if present
		var feedback = target.nextElementSibling;
		if (feedback && feedback.classList.contains('invalid-feedback')) {
			feedback.style.display = invalid ? 'block' : '';
		}
	}

	/**
	 * Custom validation for invoice line-item rows in #itemtable.
	 *
	 * Row 0 is always validated (qty, product, unit_price must all be non-empty).
	 * Rows 1+ are validated only if they have a product OR quantity entered
	 * (i.e. partially filled rows must be completed before submitting).
	 *
	 * Returns true if all checked rows are valid, false if any errors are found.
	 */
	function validateInvoiceLineItems(form) {
		var itemTable = form.querySelector('#itemtable');
		if (!itemTable) return true;

		var hasErrors = false;

		itemTable.querySelectorAll('.si-line-item').forEach(function (row, index) {
			// Skip soft-deleted rows (delete input has a value)
			var deleteInput = row.querySelector('input[name^="delete"]');
			if (deleteInput && deleteInput.value) return;

			var qtyInput       = row.querySelector('input[name^="quantity"]');
			var productSel     = row.querySelector('select[name^="products"]');
			var unitPriceInput = row.querySelector('input[name^="unit_price"]');

			var qtyVal     = qtyInput   ? qtyInput.value.trim()   : '';
			var productVal = productSel ? productSel.value.trim() : '';

			// Decide whether this row needs validation
			var shouldValidate = (index === 0) || (productVal !== '' || qtyVal !== '');

			if (!shouldValidate) {
				// Clear any stale error state from a previous submission attempt
				setInvalid(qtyInput,       false);
				setInvalid(productSel,     false);
				setInvalid(unitPriceInput, false);
				return;
			}

			// Validate quantity
			var qtyInvalid = (qtyVal === '');
			setInvalid(qtyInput, qtyInvalid);
			if (qtyInvalid) hasErrors = true;

			// Validate product
			var productInvalid = (productVal === '');
			setInvalid(productSel, productInvalid);
			if (productInvalid) hasErrors = true;

			// Validate unit price
			var priceInvalid = (unitPriceInput ? unitPriceInput.value.trim() === '' : false);
			setInvalid(unitPriceInput, priceInvalid);
			if (priceInvalid) hasErrors = true;
		});

		return !hasErrors;
	}

	/**
	 * For invoice forms (#itemtable present): apply is-invalid directly to each
	 * native-invalid field without adding was-validated to the form.
	 * This means only broken fields get highlighted — valid fields stay unstyled.
	 */
	function applyInvalidClasses(form) {
		form.querySelectorAll('input:invalid, select:invalid, textarea:invalid').forEach(function (el) {
			setInvalid(el, true);
		});
	}

	function focusFirst(form) {
		var first = form.querySelector(':invalid') || form.querySelector('.is-invalid');
		if (!first) return;
		switchToTab(first);
		var focusEl = first;
		// Tom Select wrappers aren't focusable — focus the inner text input instead.
		// Handle: (a) first IS the .ts-wrapper, (b) first is the hidden <select> whose
		// .ts-wrapper is the next sibling (Tom Select inserts it there, not as an ancestor).
		var tsWrapper = null;
		if (first.classList.contains('ts-wrapper')) {
			tsWrapper = first;
		} else if (first.tagName === 'SELECT') {
			var next = first.nextElementSibling;
			if (next && next.classList.contains('ts-wrapper')) tsWrapper = next;
		}
		if (tsWrapper) {
			var tsInput = tsWrapper.querySelector('.ts-input input');
			if (tsInput) focusEl = tsInput;
		}
		setTimeout(function () { if (focusEl.focus) focusEl.focus(); }, 80);
	}

	function validateForm(form) {
		if (!form || !form.classList.contains('needs-validation')) return true;

		var isInvoiceForm = !!form.querySelector('#itemtable');

		if (isInvoiceForm) {
			// Invoice forms: only highlight invalid fields, leave valid fields unstyled.
			// Deliberately skip was-validated (which would add green to all valid fields).
			var nativeValid    = form.checkValidity();
			var lineItemsValid = validateInvoiceLineItems(form);

			if (nativeValid && lineItemsValid) return true;

			// Apply is-invalid to any natively invalid fields (e.g. date)
			applyInvalidClasses(form);
			focusFirst(form);
			return false;
		}

		// Standard path for non-invoice forms
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
