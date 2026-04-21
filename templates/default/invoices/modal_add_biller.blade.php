{{-- Modal: quick-add a new biller from the invoice form --}}
<div class="modal modal-blur fade" id="modal-add-biller" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="ti ti-building-store me-2"></i>{{ $LANG['add_biller'] ?? 'Add New Biller' }}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ $LANG['close'] ?? 'Close' }}"></button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<label class="form-label required" for="si-new-biller-name">{{ $LANG['biller_name'] ?? $LANG['name'] ?? 'Name' }}</label>
					<input type="text" id="si-new-biller-name" class="form-control" autocomplete="off" />
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
				</div>
				<div class="row g-3 mb-3">
					<div class="col-sm-6">
						<label class="form-label" for="si-new-biller-email">{{ $LANG['email'] ?? 'Email' }}</label>
						<input type="email" id="si-new-biller-email" class="form-control" autocomplete="off" />
					</div>
					<div class="col-sm-6">
						<label class="form-label" for="si-new-biller-phone">{{ $LANG['phone'] ?? 'Phone' }}</label>
						<input type="text" id="si-new-biller-phone" class="form-control" autocomplete="off" />
					</div>
				</div>
				<div id="si-new-biller-error" class="alert alert-danger mt-3 d-none"></div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-link" data-bs-dismiss="modal">{{ $LANG['cancel'] ?? 'Cancel' }}</button>
				<button type="button" id="si-save-biller-btn" class="btn btn-primary">
					<i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? 'Save' }}
				</button>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
	var modalEl  = document.getElementById('modal-add-biller');
	var saveBtn  = document.getElementById('si-save-biller-btn');
	var nameEl   = document.getElementById('si-new-biller-name');
	var emailEl  = document.getElementById('si-new-biller-email');
	var phoneEl  = document.getElementById('si-new-biller-phone');
	var errorEl  = document.getElementById('si-new-biller-error');

	modalEl.addEventListener('hidden.bs.modal', function () {
		nameEl.classList.remove('is-invalid');
		errorEl.classList.add('d-none');
	});

	modalEl.addEventListener('shown.bs.modal', function () {
		nameEl.focus();
	});

	nameEl.addEventListener('keydown', function (e) {
		if (e.key === 'Enter') { e.preventDefault(); saveBtn.click(); }
	});

	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.si-add-biller-btn');
		if (!btn) return;
		e.preventDefault();
		nameEl.value  = '';
		emailEl.value = '';
		phoneEl.value = '';
		bootstrap.Modal.getOrCreateInstance(modalEl).show();
	});

	saveBtn.addEventListener('click', function () {
		var name = nameEl.value.trim();
		if (!name) {
			nameEl.classList.add('is-invalid');
			nameEl.focus();
			return;
		}
		nameEl.classList.remove('is-invalid');

		saveBtn.disabled = true;
		saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ $LANG['saving'] ?? 'Saving...' }}';

		var body = new FormData();
		body.append('name',  name);
		body.append('email', emailEl.value);
		body.append('phone', phoneEl.value);

		fetch('./index.php?module=invoices&view=add_biller_modal_ajax', { method: 'POST', body: body })
			.then(function (r) { return r.json(); })
			.then(function (result) {
				if (result.success) {
					bootstrap.Modal.getInstance(modalEl).hide();

					document.querySelectorAll('select[name="biller_id"]').forEach(function (sel) {
						var id   = String(result.id);
						var text = result.name;
						if (sel.tomselect) {
							sel.tomselect.addOption({ value: id, text: text });
							sel.tomselect.setValue(id);
						} else {
							Array.from(sel.options).forEach(function (o) { o.selected = false; });
							var opt = document.createElement('option');
							opt.value    = id;
							opt.text     = text;
							opt.selected = true;
							sel.appendChild(opt);
							sel.value = id;
						}
						sel.classList.remove('is-invalid');
					});

					nameEl.value  = '';
					emailEl.value = '';
					phoneEl.value = '';
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
