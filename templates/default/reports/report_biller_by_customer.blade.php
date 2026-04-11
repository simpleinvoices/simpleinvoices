@php $data = $data ?? []; @endphp

<div class="card">
	<div class="card-header">
		<span class="avatar avatar-sm bg-indigo-lt me-2 rounded"><i class="ti ti-network text-indigo"></i></span>
		<h3 class="card-title">{{ $LANG['biller_sales_by_customer_totals'] ?? '' }}</h3>
		<div class="card-options">
			<a href="index.php?module=reports&view=index" class="btn btn-sm btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['reports'] ?? 'Reports' }}
			</a>
		</div>
	</div>

	@foreach($data as $biller)
	<div class="card-body {{ !$loop->last ? 'border-bottom' : '' }} pb-2">
		<div class="d-flex align-items-center mb-3">
			<span class="avatar avatar-sm bg-indigo-lt me-2 rounded">
				<i class="ti ti-building text-indigo"></i>
			</span>
			<div>
				<div class="text-secondary small">{{ $LANG['biller_name'] ?? '' }}</div>
				<h4 class="mb-0 fw-semibold">{{ $biller['name'] ?? '' }}</h4>
			</div>
			<span class="badge bg-indigo-lt text-indigo ms-auto fs-6">
				{{ siLocal::number($biller['total_sales'] ?? 0) ?: '-' }}
			</span>
		</div>

		<div class="table-responsive">
			<table class="table table-sm table-vcenter table-hover mb-0">
				<thead>
					<tr>
						<th>{{ $LANG['customer_name'] ?? '' }}</th>
						<th class="text-end">{{ $LANG['sales'] ?? '' }}</th>
					</tr>
				</thead>
				<tbody>
				@foreach(($biller['customers'] ?? []) as $customer)
					<tr>
						<td>{{ $customer['name'] ?? '' }}</td>
						<td class="text-end fw-semibold">{{ siLocal::number($customer['sum_total'] ?? 0) ?: '-' }}</td>
					</tr>
				@endforeach
				</tbody>
				<tfoot>
					<tr class="fw-bold table-active">
						<td class="text-secondary">{{ $LANG['total'] ?? '' }}</td>
						<td class="text-end text-indigo">{{ siLocal::number($biller['total_sales'] ?? 0) ?: '-' }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	@endforeach

	@if(empty($data))
	<div class="card-body text-center text-secondary py-5">
		<i class="ti ti-building-off fs-1 d-block mb-2"></i>
		{{ $LANG['no_data'] ?? 'No data available.' }}
	</div>
	@endif
</div>
