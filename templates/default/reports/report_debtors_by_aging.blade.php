@php
	$data = $data ?? [];
	$total_owed = $total_owed ?? 0;

	// Colour map for aging buckets
	$aging_colors = [
		'0-14'  => ['bg' => 'bg-success-lt', 'text' => 'text-success',  'bar' => 'bg-success'],
		'15-30' => ['bg' => 'bg-yellow-lt',  'text' => 'text-yellow',   'bar' => 'bg-yellow'],
		'31-60' => ['bg' => 'bg-orange-lt',  'text' => 'text-orange',   'bar' => 'bg-orange'],
		'61-90' => ['bg' => 'bg-red-lt',     'text' => 'text-red',      'bar' => 'bg-red'],
		'90+'   => ['bg' => 'bg-dark',       'text' => 'text-white',    'bar' => 'bg-dark'],
	];
@endphp

<div class="card">
	<div class="card-header">
		<span class="avatar avatar-sm bg-red-lt me-2 rounded"><i class="ti ti-clock text-red"></i></span>
		<h3 class="card-title">{{ $LANG['debtors_by_aging_periods'] ?? '' }}</h3>
		<div class="card-options">
			<a href="index.php?module=reports&view=index" class="btn btn-sm btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['reports'] ?? 'Reports' }}
			</a>
		</div>
	</div>

	{{-- Summary stat --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-6 col-lg-3">
				<div class="p-3 bg-red-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total_owed'] ?? '' }}</div>
					<div class="h2 fw-bold text-red mb-0">{{ siLocal::number($total_owed) ?: '-' }}</div>
				</div>
			</div>
			@foreach($data as $bucket => $period)
			@php $c = $aging_colors[$bucket] ?? ['bg' => 'bg-secondary-lt', 'text' => 'text-secondary', 'bar' => 'bg-secondary']; @endphp
			<div class="col-sm-6 col-lg-3">
				<div class="p-3 {{ $c['bg'] }} rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $bucket }} {{ $LANG['days'] ?? 'days' }}</div>
					<div class="h4 fw-bold {{ $c['text'] }} mb-0">{{ siLocal::number($period['sum_total'] ?? 0) ?: '-' }}</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>

	@foreach($data as $bucket => $period)
	@php $c = $aging_colors[$bucket] ?? ['bg' => 'bg-secondary-lt', 'text' => 'text-secondary', 'bar' => 'bg-secondary']; @endphp

	<div class="card-body pb-0 pt-3 border-bottom">
		<div class="d-flex align-items-center mb-2">
			<span class="badge {{ $c['bg'] }} {{ $c['text'] }} me-2 fs-6">
				<i class="ti ti-clock me-1"></i>{{ $LANG['aging'] ?? '' }}: {{ $period['name'] ?? $bucket }}
			</span>
			<span class="text-secondary small">
				{{ count($period['invoices'] ?? []) }} {{ $LANG['invoices'] ?? '' }}
				&mdash; {{ $LANG['total'] ?? '' }}: <strong>{{ siLocal::number($period['sum_total'] ?? 0) ?: '-' }}</strong>
			</span>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-sm table-vcenter table-hover card-table mb-0">
			<thead>
				<tr>
					<th>{{ $LANG['invoice_id'] ?? '' }}</th>
					<th>{{ $LANG['invoice'] ?? '' }}</th>
					<th>{{ $LANG['biller'] ?? '' }}</th>
					<th>{{ $LANG['customer'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['paid'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['owing'] ?? '' }}</th>
					<th>{{ ucfirst($LANG['date'] ?? '') }}</th>
					<th class="text-end">{{ $LANG['age'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach(($period['invoices'] ?? []) as $invoice)
				<tr>
					<td class="text-secondary">{{ $invoice['id'] ?? '' }}</td>
					<td class="fw-medium">
						{{ $invoice['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }}
						{{ $invoice['index_id'] ?? '' }}
					</td>
					<td>{{ $invoice['biller'] ?? '' }}</td>
					<td>{{ $invoice['customer'] ?? '' }}</td>
					<td class="text-end">{{ siLocal::number($invoice['inv_total'] ?? 0) ?: '-' }}</td>
					<td class="text-end text-secondary">{{ siLocal::number($invoice['inv_paid'] ?? 0) ?: '-' }}</td>
					<td class="text-end fw-bold {{ $c['text'] }}">{{ siLocal::number($invoice['inv_owing'] ?? 0) ?: '-' }}</td>
					<td class="text-secondary">{{ $invoice['date'] ?? '' }}</td>
					<td class="text-end">
						<span class="badge {{ $c['bg'] }} {{ $c['text'] }}">{{ $invoice['age'] ?? '' }}d</span>
					</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold table-active">
					<td colspan="6" class="text-secondary">{{ $LANG['total'] ?? '' }}</td>
					<td class="text-end {{ $c['text'] }}">{{ siLocal::number($period['sum_total'] ?? 0) ?: '-' }}</td>
					<td colspan="2"></td>
				</tr>
			</tfoot>
		</table>
	</div>
	@endforeach

	@if(empty($data))
	<div class="card-body text-center text-secondary py-5">
		<i class="ti ti-clock-off fs-1 d-block mb-2 text-success"></i>
		<div class="fw-semibold">{{ $LANG['no_outstanding_invoices'] ?? 'No outstanding invoices.' }}</div>
	</div>
	@endif
</div>
