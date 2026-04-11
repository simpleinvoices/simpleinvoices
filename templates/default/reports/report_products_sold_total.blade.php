@php
	$data = $data ?? [];
	$total_quantity = $total_quantity ?? 0;
	$product_count = count($data);
@endphp

<div class="card">
	<div class="card-header">
		<span class="avatar avatar-sm bg-cyan-lt me-2 rounded"><i class="ti ti-shopping-cart text-cyan"></i></span>
		<h3 class="card-title">{{ $LANG['product_sales'] ?? '' }}</h3>
		<div class="card-options">
			<a href="index.php?module=reports&view=index" class="btn btn-sm btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['reports'] ?? 'Reports' }}
			</a>
		</div>
	</div>

	{{-- Summary stats --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-6">
				<div class="p-3 bg-cyan-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total'] ?? '' }}</div>
					<div class="h2 fw-bold text-cyan mb-0">{{ siLocal::number($total_quantity) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['products'] ?? 'Products' }}</div>
					<div class="h2 fw-bold text-blue mb-0">{{ $product_count }}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>#</th>
					<th>{{ $LANG['product'] ?? 'Product' }}</th>
					<th class="text-end">{{ $LANG['quantity'] ?? 'Qty' }}</th>
					<th class="w-25 d-none d-md-table-cell"></th>
				</tr>
			</thead>
			<tbody>
			@foreach($data as $i => $customer)
				@php $pct = $total_quantity > 0 ? round(($customer['sum_quantity'] / $total_quantity) * 100) : 0; @endphp
				<tr>
					<td class="text-secondary">{{ $i + 1 }}</td>
					<td class="fw-medium">{{ $customer['description'] ?? '' }}</td>
					<td class="text-end fw-semibold">{{ siLocal::number($customer['sum_quantity'] ?? 0) ?: '-' }}</td>
					<td class="d-none d-md-table-cell">
						<div class="d-flex align-items-center gap-2">
							<div class="progress flex-grow-1" style="height:6px;">
								<div class="progress-bar bg-cyan" style="width:{{ $pct }}%"></div>
							</div>
							<span class="text-secondary small" style="min-width:35px;">{{ $pct }}%</span>
						</div>
					</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold">
					<td></td>
					<td class="text-secondary">{{ $LANG['total'] ?? '' }}</td>
					<td class="text-end text-cyan">{{ siLocal::number($total_quantity) ?: '-' }}</td>
					<td class="d-none d-md-table-cell"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
