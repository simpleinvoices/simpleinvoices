@php
	$data = $data ?? [];
	$total_sales = $total_sales ?? 0;
	$biller_count = count($data);
@endphp

<div class="card">
	<div class="card-header">
		<span class="avatar avatar-sm bg-indigo-lt me-2 rounded"><i class="ti ti-building-store text-indigo"></i></span>
		<h3 class="card-title">{{ $LANG['biller_sales'] ?? '' }}</h3>
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
				<div class="p-3 bg-indigo-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total_sales'] ?? '' }}</div>
					<div class="h2 fw-bold text-indigo mb-0">{{ siLocal::number($total_sales) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['billers'] ?? 'Billers' }}</div>
					<div class="h2 fw-bold text-blue mb-0">{{ $biller_count }}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>#</th>
					<th>{{ $LANG['biller'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total_sales'] ?? '' }}</th>
					<th class="w-25 d-none d-md-table-cell"></th>
				</tr>
			</thead>
			<tbody>
			@foreach($data as $i => $biller)
				@php $pct = $total_sales > 0 ? round(($biller['sum_total'] / $total_sales) * 100) : 0; @endphp
				<tr>
					<td class="text-secondary">{{ $i + 1 }}</td>
					<td class="fw-medium">{{ $biller['name'] ?? '' }}</td>
					<td class="text-end fw-semibold">{{ siLocal::number($biller['sum_total'] ?? 0) ?: '-' }}</td>
					<td class="d-none d-md-table-cell">
						<div class="d-flex align-items-center gap-2">
							<div class="progress flex-grow-1" style="height:6px;">
								<div class="progress-bar bg-indigo" style="width:{{ $pct }}%"></div>
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
					<td class="text-secondary">{{ $LANG['total_sales'] ?? '' }}</td>
					<td class="text-end text-indigo">{{ siLocal::number($total_sales) ?: '-' }}</td>
					<td class="d-none d-md-table-cell"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
