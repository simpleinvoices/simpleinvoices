<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>{{ $LANG['payment'] ?? 'Payment' }} {{ $LANG['receipt'] ?? 'Receipt' }} – #{{ $payment['id'] ?? '' }}</title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="https://preview.tabler.io/dist/css/tabler-azure.min.css" />
	<style type="text/css" media="all">
		body { background: #fff; color: #1e293b; font-size: 14px; padding: 2rem; }
		@media print { body { padding: 0; } .no-print { display: none !important; } }
		.si-print-container { max-width: 800px; margin: 0 auto; }
		.si-print-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; }
		.si-print-logo { max-height: 48px; }
		.si-print-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; }
		.si-print-two-col { display: flex; gap: 2rem; margin-bottom: 2rem; }
		.si-print-two-col > div { flex: 1; }
		.si-print-label { font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; margin-bottom: 0.35rem; }
		.si-print-name { font-weight: 600; margin-bottom: 0.25rem; }
		.si-print-address { color: #475569; line-height: 1.5; }
		.si-print-h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 1.25rem; }
		.si-print-table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
		.si-print-table th { text-align: left; padding: 0.5rem 0.75rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; border-bottom: 1px solid #e2e8f0; }
		.si-print-table th.text-end { text-align: right; }
		.si-print-table td { padding: 0.75rem; border-bottom: 1px solid #f1f5f9; }
		.si-print-table td.text-end { text-align: right; font-weight: 600; }
		.si-print-total { margin-top: 1rem; text-align: right; }
		.si-print-total .amount { font-size: 1.25rem; font-weight: 700; }
		.si-print-notes { margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; }
		.si-print-notes-title { font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; color: #64748b; margin-bottom: 0.35rem; }
	</style>
	@if(!empty($css))
	<link rel="stylesheet" type="text/css" href="{{ $css }}" media="all" />
	@endif
</head>
<body>
	<div class="si-print-container">
		{{-- Header: logo + title (Tabler invoice style) --}}
		<div class="si-print-header">
			<div>
				@if(!empty($logo))
				<img src="{{ $logo }}" alt="" class="si-print-logo" />
				@endif
			</div>
			<div class="si-print-title">
				{{ $LANG['payment'] ?? 'Payment' }} {{ $LANG['receipt'] ?? 'Receipt' }} #{{ $payment['id'] ?? '' }}
			</div>
		</div>

		{{-- Company (biller) and Client (customer) – two columns --}}
		<div class="si-print-two-col">
			<div>
				<div class="si-print-label">{{ $LANG['biller'] ?? 'Company' }}</div>
				<div class="si-print-name">{{ $biller['name'] ?? '' }}</div>
				<div class="si-print-address">
					@if(!empty($biller['street_address'])){{ $biller['street_address'] ?? '' }}<br />@endif
					@if(!empty($biller['street_address2'])){{ $biller['street_address2'] ?? '' }}<br />@endif
					@if(!empty($biller['city']) || !empty($biller['state']) || !empty($biller['zip_code']))
						{{ $biller['city'] ?? '' }}{{ !empty($biller['city']) && (!empty($biller['state']) || !empty($biller['zip_code'])) ? ', ' : '' }}{{ $biller['state'] ?? '' }}{{ !empty($biller['state']) && !empty($biller['zip_code']) ? ' ' : '' }}{{ $biller['zip_code'] ?? '' }}<br />
					@endif
					@if(!empty($biller['country'])){{ $biller['country'] ?? '' }}<br />@endif
					@if(!empty($biller['email'])){{ $biller['email'] ?? '' }}@endif
				</div>
			</div>
			<div>
				<div class="si-print-label">{{ $LANG['customer'] ?? 'Client' }}</div>
				<div class="si-print-name">{{ $customer['name'] ?? '' }}</div>
				<div class="si-print-address">
					@if(!empty($customer['attention'])){{ $LANG['attention_short'] ?? 'Attn' }}: {{ $customer['attention'] ?? '' }}<br />@endif
					@if(!empty($customer['street_address'])){{ $customer['street_address'] ?? '' }}<br />@endif
					@if(!empty($customer['street_address2'])){{ $customer['street_address2'] ?? '' }}<br />@endif
					@if(!empty($customer['city']) || !empty($customer['state']) || !empty($customer['zip_code']))
						{{ $customer['city'] ?? '' }}{{ !empty($customer['city']) && (!empty($customer['state']) || !empty($customer['zip_code'])) ? ', ' : '' }}{{ $customer['state'] ?? '' }}{{ !empty($customer['state']) && !empty($customer['zip_code']) ? ' ' : '' }}{{ $customer['zip_code'] ?? '' }}<br />
					@endif
					@if(!empty($customer['country'])){{ $customer['country'] ?? '' }}<br />@endif
					@if(!empty($customer['phone'])){{ $LANG['phone_short'] ?? 'Phone' }}: {{ $customer['phone'] ?? '' }}<br />@endif
					@if(!empty($customer['email'])){{ $customer['email'] ?? '' }}@endif
				</div>
			</div>
		</div>

		{{-- Payment reference --}}
		<h2 class="si-print-h2">{{ $LANG['payment'] ?? 'Payment' }} #{{ $payment['id'] ?? '' }}</h2>

		{{-- Payment details table --}}
		<table class="si-print-table">
			<thead>
				<tr>
					<th>{{ $LANG['payment_id'] ?? 'ID' }}</th>
					<th>{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $LANG['id'] ?? '' }}</th>
					<th>{{ $LANG['date_upper'] ?? 'Date' }}</th>
					<th>{{ $LANG['payment_type'] ?? 'Type' }}</th>
					<th class="text-end">{{ $LANG['amount'] ?? 'Amount' }}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{{ $payment['id'] ?? '' }}</td>
					<td>{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $invoice['index_id'] ?? $payment['ac_inv_id'] ?? '' }}</td>
					<td>{{ $payment['date'] ?? '' }}</td>
					<td>{{ $paymentType['pt_description'] ?? '' }}</td>
					<td class="text-end">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($payment['ac_amount'] ?? 0) }}</td>
				</tr>
			</tbody>
		</table>

		{{-- Total --}}
		<div class="si-print-total">
			<span class="text-secondary">{{ $LANG['amount'] ?? 'Amount' }}</span>
			<span class="amount">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($payment['ac_amount'] ?? 0) }}</span>
		</div>

		@if(!empty($payment['online_payment_id']))
			<div class="si-print-address mt-2" style="font-size: 0.8125rem;">
				{{ $LANG['online_payment_id'] ?? 'Online Payment ID' }}: {{ $payment['online_payment_id'] ?? '' }}
			</div>
		@endif

		@if(!empty($payment['ac_notes']))
			<div class="si-print-notes">
				<div class="si-print-notes-title">{{ $LANG['notes'] ?? 'Notes' }}</div>
				<div>{!! outhtml($payment['ac_notes'] ?? '') !!}</div>
			</div>
		@endif
	</div>
	<script>
		// Optional: auto-print when opened in new window
		// window.onload = function() { window.print(); };
	</script>
</body>
</html>
