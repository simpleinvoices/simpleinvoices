{{-- Modal: quick-add a new product from the invoice form --}}
<div class="modal modal-blur fade" id="modal-add-product" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="ti ti-package me-2"></i>{{ $LANG['add_product'] ?? 'Add New Product' }}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ $LANG['close'] ?? 'Close' }}"></button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<label class="form-label required" for="si-new-product-description">{{ $LANG['description'] ?? 'Description' }}</label>
					<input type="text" id="si-new-product-description" class="form-control" autocomplete="off" />
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
				</div>
				<div class="row g-3 mb-3">
					<div class="col-sm-6">
						<label class="form-label" for="si-new-product-price">{{ $LANG['unit_price'] ?? 'Unit Price' }}</label>
						<input type="text" id="si-new-product-price" class="form-control" value="0.00" />
					</div>
					<div class="col-sm-6">
						<label class="form-label" for="si-new-product-tax">{{ $LANG['tax'] ?? 'Default Tax' }}</label>
						<select id="si-new-product-tax" class="form-select">
							<option value="">{{ $LANG['none'] ?? 'None' }}</option>
							@foreach(($taxes ?? []) as $taxOpt)
								<option value="{{ $taxOpt['tax_id'] ?? '' }}">{{ $taxOpt['tax_description'] ?? '' }} ({{ ($taxOpt['type'] ?? '') === '$' ? '$' : '' }}{{ (float)($taxOpt['tax_percentage'] ?? 0) }}{{ ($taxOpt['type'] ?? '') !== '$' ? '%' : '' }})</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="mb-0">
					<label class="form-label" for="si-new-product-notes">{{ $LANG['notes'] ?? 'Notes' }}</label>
					<textarea id="si-new-product-notes" class="form-control" rows="2"></textarea>
				</div>
				<div id="si-new-product-error" class="alert alert-danger mt-3 d-none"></div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-link" data-bs-dismiss="modal">{{ $LANG['cancel'] ?? 'Cancel' }}</button>
				<button type="button" id="si-save-product-btn" class="btn btn-primary">
					<i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? 'Save' }}
				</button>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
	var modalEl  = document.getElementById('modal-add-product');
	var saveBtn  = document.getElementById('si-save-product-btn');
	var descEl   = document.getElementById('si-new-product-description');
	var priceEl  = document.getElementById('si-new-product-price');
	var taxEl    = document.getElementById('si-new-product-tax');
	var notesEl  = document.getElementById('si-new-product-notes');
	var errorEl  = document.getElementById('si-new-product-error');

	// Cleared on modal hide so a cancelled open doesn't leave a dangling reference
	modalEl.addEventListener('hidden.bs.modal', function () {
		window._siProductModalCallback  = null;
		window._siProductModalTomSelect = null;
		descEl.classList.remove('is-invalid');
		errorEl.classList.add('d-none');
	});

	modalEl.addEventListener('shown.bs.modal', function () {
		descEl.focus();
	});

	descEl.addEventListener('keydown', function (e) {
		if (e.key === 'Enter') { e.preventDefault(); saveBtn.click(); }
	});

	// Clicking any ".si-add-product-row-btn" button in a line-item row
	// stores that row's Tom Select instance, then opens this modal.
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.si-add-product-row-btn');
		if (!btn) return;
		e.preventDefault();
		var rowEl = btn.closest('.si-line-item');
		var sel   = rowEl && rowEl.querySelector('select.product_change');
		window._siProductModalTomSelect = (sel && sel.tomselect) ? sel.tomselect : null;
		window._siProductModalCallback  = null;
		descEl.value  = '';
		priceEl.value = '0.00';
		taxEl.value   = '';
		notesEl.value = '';
		bootstrap.Modal.getOrCreateInstance(modalEl).show();
	});

	saveBtn.addEventListener('click', function () {
		var description = descEl.value.trim();
		if (!description) {
			descEl.classList.add('is-invalid');
			descEl.focus();
			return;
		}
		descEl.classList.remove('is-invalid');

		saveBtn.disabled = true;
		saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ $LANG['saving'] ?? 'Saving...' }}';

		var body = new FormData();
		body.append('description',    description);
		body.append('unit_price',     priceEl.value || '0.00');
		body.append('default_tax_id', taxEl.value);
		body.append('notes',          notesEl.value);

		fetch('./index.php?module=invoices&view=add_product_modal_ajax', { method: 'POST', body: body })
			.then(function (r) { return r.json(); })
			.then(function (result) {
				if (result.success) {
					bootstrap.Modal.getInstance(modalEl).hide();

					var id   = String(result.id);
					var desc = result.description;

					// Determine which Tom Select triggered the modal
					var triggerTs = window._siProductModalTomSelect || null;

					if (typeof window._siProductModalCallback === 'function') {
						// Tom Select "type-to-create" path: callback adds + selects in one step
						window._siProductModalCallback({ id: id, description: desc });
					} else if (triggerTs) {
						// "+" button path: add the option then select it.
						// onChange fires automatically → invoice_product_change() fills price/tax/notes.
						triggerTs.addOption({ id: id, description: desc });
						triggerTs.setValue(id);
					}

					// Add the new product to every other product dropdown on the page
					// so it's available without a page reload.
					document.querySelectorAll('select.product_change').forEach(function (sel) {
						var ts = sel.tomselect;
						if (!ts || ts === triggerTs) return;
						ts.addOption({ id: id, description: desc });
						ts.refreshOptions(false); // update the dropdown list without opening it
					});

					descEl.value  = '';
					priceEl.value = '0.00';
					taxEl.value   = '';
					notesEl.value = '';
				} else {
					errorEl.textContent = result.error || '{{ $LANG['error'] ?? 'Error' }}';
					errorEl.classList.remove('d-none');
				}
			})
			.catch(function () {
				errorEl.textContent = '{{ $LANG['error'] ?? 'An error occurred' }}';
				errorEl.classList.remove('d-none');
			})
			.finally(function () {
				saveBtn.disabled = false;
				saveBtn.innerHTML = '<i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? 'Save' }}';
			});
	});
}());
</script>
