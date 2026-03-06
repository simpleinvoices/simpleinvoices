{{-- Payment details – Tabler invoice-style layout (see https://preview.tabler.io/invoice.html) --}}
<div class="card">
	<div class="card-header">
		<div class="card-actions">
			<a href="index.php?module=payments&amp;view=print&amp;id={{ urlencode($payment['id'] ?? '') }}" target="_blank" class="btn btn-outline-primary">
				<i class="ti ti-printer me-1"></i>{{ $LANG['print_preview'] ?? 'Print' }}
			</a>
			<a href="./index.php?module=payments&view=manage" class="btn btn-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['cancel'] ?? 'Cancel' }}
			</a>
		</div>
	</div>
	<div class="card-body">
		{{-- Company (biller) and Client (customer) – two columns like Tabler invoice --}}
		<div class="row mb-4">
			<div class="col-md-6">
				<div class="text-secondary text-uppercase fs-6 fw-semibold mb-2">{{ $LANG['biller'] ?? 'Company' }}</div>
				<div class="fw-semibold">{{ $biller['name'] ?? '' }}</div>
				@if(!empty($biller['street_address']))
					<div>{{ $biller['street_address'] ?? '' }}</div>
				@endif
				@if(!empty($biller['street_address2']))
					<div>{{ $biller['street_address2'] ?? '' }}</div>
				@endif
				@if(!empty($biller['city']) || !empty($biller['state']) || !empty($biller['zip_code']))
					<div>{{ $biller['city'] ?? '' }}{{ !empty($biller['city']) && (!empty($biller['state']) || !empty($biller['zip_code'])) ? ', ' : '' }}{{ $biller['state'] ?? '' }}{{ !empty($biller['state']) && !empty($biller['zip_code']) ? ' ' : '' }}{{ $biller['zip_code'] ?? '' }}</div>
				@endif
				@if(!empty($biller['country']))
					<div>{{ $biller['country'] ?? '' }}</div>
				@endif
				@if(!empty($biller['email']))
					<div class="mt-1"><a href="mailto:{{ $biller['email'] ?? '' }}">{{ $biller['email'] ?? '' }}</a></div>
				@endif
			</div>
			<div class="col-md-6">
				<div class="text-secondary text-uppercase fs-6 fw-semibold mb-2">{{ $LANG['customer'] ?? 'Client' }}</div>
				<div class="fw-semibold">{{ $customer['name'] ?? '' }}</div>
				@if(!empty($customer['street_address']))
					<div>{{ $customer['street_address'] ?? '' }}</div>
				@endif
				@if(!empty($customer['street_address2']))
					<div>{{ $customer['street_address2'] ?? '' }}</div>
				@endif
				@if(!empty($customer['city']) || !empty($customer['state']) || !empty($customer['zip_code']))
					<div>{{ $customer['city'] ?? '' }}{{ !empty($customer['city']) && (!empty($customer['state']) || !empty($customer['zip_code'])) ? ', ' : '' }}{{ $customer['state'] ?? '' }}{{ !empty($customer['state']) && !empty($customer['zip_code']) ? ' ' : '' }}{{ $customer['zip_code'] ?? '' }}</div>
				@endif
				@if(!empty($customer['country']))
					<div>{{ $customer['country'] ?? '' }}</div>
				@endif
				@if(!empty($customer['email']))
					<div class="mt-1"><a href="mailto:{{ $customer['email'] ?? '' }}">{{ $customer['email'] ?? '' }}</a></div>
				@endif
			</div>
		</div>

		{{-- Payment reference (like Invoice # on Tabler) --}}
		<h2 class="mb-4">{{ $LANG['payment'] ?? 'Payment' }} #{{ $payment['id'] ?? '' }}</h2>

		{{-- Payment details table – Tabler invoice table style --}}
		<div class="table-responsive">
			<table class="table table-vcenter card-table table-striped">
				<thead>
					<tr>
						<th>{{ $LANG['payment_id'] ?? 'ID' }}</th>
						<th>{{ $LANG['invoice_id'] ?? 'Invoice' }}</th>
						<th>{{ $LANG['date_upper'] ?? 'Date' }}</th>
						<th>{{ $LANG['payment_type'] ?? 'Type' }}</th>
						<th class="text-end">{{ $LANG['amount'] ?? 'Amount' }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ $payment['id'] ?? '' }}</td>
						<td>
							<a href="index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($payment['ac_inv_id'] ?? '') }}&amp;action=view">{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $invoice['index_id'] ?? $payment['ac_inv_id'] ?? '' }}</a>
						</td>
						<td>{{ $payment['date'] ?? '' }}</td>
						<td>{{ $paymentType['pt_description'] ?? '' }}</td>
						<td class="text-end fw-semibold">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($payment['ac_amount'] ?? 0) }}</td>
					</tr>
				</tbody>
			</table>
		</div>

		{{-- Totals row (Tabler-style: Subtotal / Total Due) --}}
		<div class="row justify-content-end mt-2">
			<div class="col-12 col-sm-6 col-md-4">
				<table class="table table-sm table-borderless mb-0">
					<tr>
						<td class="text-secondary">{{ $LANG['amount'] ?? 'Amount' }}</td>
						<td class="text-end fw-bold fs-4">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($payment['ac_amount'] ?? 0) }}</td>
					</tr>
				</table>
			</div>
		</div>

		@if(!empty($payment['online_payment_id']))
			<div class="mt-3 text-secondary small">
				{{ $LANG['online_payment_id'] ?? 'Online Payment ID' }}: {{ $payment['online_payment_id'] ?? '' }}
			</div>
		@endif

		@if(!empty($payment['ac_notes']))
			<div class="mt-4 pt-3 border-top">
				<div class="text-secondary text-uppercase fs-6 fw-semibold mb-2">{{ $LANG['notes'] ?? 'Notes' }}</div>
				<div class="text-body">{!! outhtml($payment['ac_notes'] ?? '') !!}</div>
			</div>
		@endif
	</div>
	<div class="card-footer text-end bg-transparent">
		<a href="./index.php?module=payments&view=manage" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>{{ $LANG['cancel'] ?? 'Cancel' }}</a>
		<a href="index.php?module=payments&amp;view=print&amp;id={{ urlencode($payment['id'] ?? '') }}" target="_blank" class="btn btn-primary"><i class="ti ti-printer me-1"></i>{{ $LANG['print_preview'] ?? 'Print' }}</a>
	</div>
</div>
