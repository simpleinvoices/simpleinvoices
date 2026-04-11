@php $data = $data ?? []; @endphp

<div class="card">
	<div class="card-header">
		<span class="avatar avatar-sm bg-cyan-lt me-2 rounded"><i class="ti ti-users-group text-cyan"></i></span>
		<h3 class="card-title">{{ $LANG['products_by_customer'] ?? '' }}</h3>
		<div class="card-options">
			<a href="index.php?module=reports&view=index" class="btn btn-sm btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['reports'] ?? 'Reports' }}
			</a>
		</div>
	</div>

	@foreach($data as $customer)
	<div class="card-body {{ !$loop->last ? 'border-bottom' : '' }} pb-2">
		<div class="d-flex align-items-center mb-3">
			<span class="avatar avatar-sm bg-cyan-lt me-2 rounded">
				<i class="ti ti-user text-cyan"></i>
			</span>
			<h4 class="mb-0 fw-semibold">{{ $customer['name'] ?? '' }}</h4>
			<span class="badge bg-cyan-lt text-cyan ms-auto">
				{{ $LANG['total'] ?? '' }}: {{ siLocal::number($customer['total_quantity'] ?? 0) ?: '-' }}
			</span>
		</div>

		<div class="table-responsive">
			<table class="table table-sm table-vcenter table-hover mb-0">
				<thead>
					<tr>
						<th>{{ $LANG['product'] ?? 'Product' }}</th>
						<th class="text-end">{{ $LANG['quantity'] ?? 'Qty' }}</th>
					</tr>
				</thead>
				<tbody>
				@foreach(($customer['products'] ?? []) as $product)
					<tr>
						<td>{{ $product['description'] ?? '' }}</td>
						<td class="text-end fw-semibold">{{ siLocal::number($product['sum_quantity'] ?? 0) ?: '-' }}</td>
					</tr>
				@endforeach
				</tbody>
				<tfoot>
					<tr class="fw-bold table-active">
						<td class="text-secondary">{{ $LANG['total'] ?? '' }}</td>
						<td class="text-end text-cyan">{{ siLocal::number($customer['total_quantity'] ?? 0) ?: '-' }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	@endforeach

	@if(empty($data))
	<div class="card-body text-center text-secondary py-5">
		<i class="ti ti-package-off fs-1 d-block mb-2"></i>
		{{ $LANG['no_data'] ?? 'No data available.' }}
	</div>
	@endif
</div>
