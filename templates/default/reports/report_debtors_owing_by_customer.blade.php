@php
	$data = $data ?? [];
	$total_owed = $total_owed ?? 0;
	$customer_count = count($data);
@endphp

<div class="card">
	<div class="card-header">
		<span class="avatar avatar-sm bg-red-lt me-2 rounded"><i class="ti ti-user-dollar text-red"></i></span>
		<h3 class="card-title">{{ $LANG['total_owed_per_customer'] ?? '' }}</h3>
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
				<div class="p-3 bg-red-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total_owing'] ?? '' }}</div>
					<div class="h2 fw-bold text-red mb-0">{{ siLocal::number($total_owed) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="p-3 bg-orange-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['customers'] ?? 'Customers' }}</div>
					<div class="h2 fw-bold text-orange mb-0">{{ $customer_count }}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>{{ $LANG['id'] ?? '' }}</th>
					<th>{{ $LANG['customer'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['paid'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['owing'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach($data as $customer)
				<tr>
					<td class="text-secondary">{{ $customer['cid'] ?? '' }}</td>
					<td class="fw-medium">{{ $customer['customer'] ?? '' }}</td>
					<td class="text-end">{{ siLocal::number($customer['inv_total'] ?? 0) ?: '-' }}</td>
					<td class="text-end text-secondary">{{ siLocal::number($customer['inv_paid'] ?? 0) ?: '-' }}</td>
					<td class="text-end fw-bold text-red">{{ siLocal::number($customer['inv_owing'] ?? 0) ?: '-' }}</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold">
					<td colspan="4" class="text-end text-secondary">{{ $LANG['total_owing'] ?? '' }}</td>
					<td class="text-end text-red">{{ siLocal::number($total_owed) ?: '-' }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
