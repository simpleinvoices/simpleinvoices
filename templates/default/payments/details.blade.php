{{-- Payment details – Tabler invoice-style layout (see https://preview.tabler.io/invoice.html) --}}
<div class="card">
	<div class="card-header">
		<div class="card-actions">
			<a href="index.php?module=payments&amp;view=print&amp;id={{ urlencode($payment['id'] ?? '') }}" class="btn btn-outline-primary si-preview-link" data-preview-title="{{ $LANG['print_preview'] ?? '' }}" data-preview-pdf="index.php?module=export&amp;view=payment&amp;id={{ urlencode($payment['id'] ?? '') }}&amp;format=pdf">
				<i class="ti ti-printer me-1"></i>{{ $LANG['print_preview'] ?? '' }}
			</a>
			<a href="./index.php?module=payments&view=manage" class="btn btn-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['cancel'] ?? '' }}
			</a>
		</div>
	</div>
	<div class="card-body">
		{{-- Company (biller) and Client (customer) – two columns like Tabler invoice --}}
		<div class="row mb-4">
			<div class="col-md-6">
				<div class="text-secondary text-uppercase fs-6 fw-semibold mb-2">{{ $LANG['biller'] ?? '' }}</div>
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
				<div class="text-secondary text-uppercase fs-6 fw-semibold mb-2">{{ $LANG['customer'] ?? '' }}</div>
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
		<h2 class="mb-4">{{ $LANG['payment'] ?? '' }} #{{ $payment['id'] ?? '' }}</h2>

		{{-- Payment details table – Tabler invoice table style --}}
		<div class="table-responsive">
			<table class="table table-vcenter card-table">
				<thead>
					<tr>
						<th>{{ $LANG['payment_id'] ?? '' }}</th>
						<th>{{ $LANG['invoice_id'] ?? '' }}</th>
						<th>{{ $LANG['date_upper'] ?? '' }}</th>
						<th>{{ $LANG['payment_type'] ?? '' }}</th>
						<th class="text-end">{{ $LANG['amount'] ?? '' }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ $payment['id'] ?? '' }}</td>
						<td>
							<a href="index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($payment['ac_inv_id'] ?? '') }}&amp;action=view">{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $invoice['index_id'] ?? $payment['ac_inv_id'] ?? '' }}</a>
						</td>
						<td>{{ $payment['date'] ?? '' }}</td>
						<td>{{ $paymentType['pt_description'] ?? '' }}</td>
						<td class="text-end fw-semibold">{!! CurrencySignHelper::formatInvoice($payment['ac_amount'] ?? 0, $invoice, $preference) !!}</td>
					</tr>
				</tbody>
			</table>
		</div>

		{{-- Totals row (Tabler-style: Subtotal / Total Due) --}}
		<div class="row justify-content-end mt-2">
			<div class="col-12 col-sm-6 col-md-4">
				<table class="table table-sm table-borderless mb-0">
					<tr>
						<td class="text-secondary">{{ $LANG['amount'] ?? '' }}</td>
						<td class="text-end fw-bold fs-4">{!! CurrencySignHelper::formatInvoice($payment['ac_amount'] ?? 0, $invoice, $preference) !!}</td>
					</tr>
				</table>
			</div>
		</div>

		@if(!empty($payment['online_payment_id']))
			<div class="mt-3 text-secondary small">
				{{ $LANG['online_payment_id'] ?? '' }}: {{ $payment['online_payment_id'] ?? '' }}
			</div>
		@endif

		@if(!empty($payment['ac_notes']))
			<div class="mt-4 pt-3 border-top">
				<div class="text-secondary text-uppercase fs-6 fw-semibold mb-2">{{ $LANG['notes'] ?? '' }}</div>
				<div class="text-body">{!! outhtml($payment['ac_notes'] ?? '') !!}</div>
			</div>
		@endif
	</div>
	<div class="card-footer bg-transparent">
		<div class="d-flex">
			<a href="./index.php?module=payments&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="index.php?module=payments&amp;view=print&amp;id={{ urlencode($payment['id'] ?? '') }}" class="btn btn-primary ms-auto si-preview-link" data-preview-title="{{ $LANG['print_preview'] ?? '' }}" data-preview-pdf="index.php?module=export&amp;view=payment&amp;id={{ urlencode($payment['id'] ?? '') }}&amp;format=pdf"><i class="ti ti-printer me-1"></i>{{ $LANG['print_preview'] ?? '' }}</a>
		</div>
	</div>
</div>
