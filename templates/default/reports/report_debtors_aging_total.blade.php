@php
	$data = $data ?? [];
	$sum_total = $sum_total ?? 0;
	$sum_paid  = $sum_paid  ?? 0;
	$sum_owing = $sum_owing ?? 0;

	$aging_colors = [
		'0-14'  => ['bg' => 'bg-success-lt', 'text' => 'text-success'],
		'15-30' => ['bg' => 'bg-yellow-lt',  'text' => 'text-yellow'],
		'31-60' => ['bg' => 'bg-orange-lt',  'text' => 'text-orange'],
		'61-90' => ['bg' => 'bg-red-lt',     'text' => 'text-red'],
		'90+'   => ['bg' => 'bg-dark',       'text' => 'text-white'],
	];
@endphp

<div class="card">
	<div class="card-header">
		<span class="avatar avatar-sm bg-red-lt me-2 rounded"><i class="ti ti-calendar-clock text-red"></i></span>
		<h3 class="card-title">{{ $LANG['total_by_aging_periods'] ?? '' }}</h3>
		<div class="card-options">
			<a href="index.php?module=reports&view=index" class="btn btn-sm btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['reports'] ?? 'Reports' }}
			</a>
		</div>
	</div>

	{{-- Grand totals summary --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-4">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total'] ?? '' }}</div>
					<div class="h3 fw-bold text-blue mb-0">{{ siLocal::number($sum_total) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="p-3 bg-green-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['paid'] ?? '' }}</div>
					<div class="h3 fw-bold text-green mb-0">{{ siLocal::number($sum_paid) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="p-3 bg-red-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['owing'] ?? '' }}</div>
					<div class="h3 fw-bold text-red mb-0">{{ siLocal::number($sum_owing) ?: '-' }}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>{{ $LANG['aging'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['paid'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['owing'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach($data as $period)
				@php
					$bucket = $period['aging'] ?? '';
					$c = $aging_colors[$bucket] ?? ['bg' => 'bg-secondary-lt', 'text' => 'text-secondary'];
				@endphp
				<tr>
					<td>
						<span class="badge {{ $c['bg'] }} {{ $c['text'] }}">
							<i class="ti ti-clock me-1"></i>{{ $bucket }}
						</span>
					</td>
					<td class="text-end">{{ siLocal::number($period['inv_total'] ?? 0) ?: '-' }}</td>
					<td class="text-end text-secondary">{{ siLocal::number($period['inv_paid'] ?? 0) ?: '-' }}</td>
					<td class="text-end fw-bold {{ $c['text'] }}">{{ siLocal::number($period['inv_owing'] ?? 0) ?: '-' }}</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold table-active">
					<td class="text-secondary">{{ $LANG['total'] ?? '' }}</td>
					<td class="text-end">{{ siLocal::number($sum_total) ?: '-' }}</td>
					<td class="text-end text-secondary">{{ siLocal::number($sum_paid) ?: '-' }}</td>
					<td class="text-end text-red">{{ siLocal::number($sum_owing) ?: '-' }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
