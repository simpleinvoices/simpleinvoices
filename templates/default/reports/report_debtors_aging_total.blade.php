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

	@if(count($data) > 0)
	{{-- Chart: grouped bars — total / paid / owing per aging period --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-aging-total"></div>
	</div>
	@endif

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

@if(count($data) > 0)
<script>
(function () {
	var periods  = @json(array_column($data, 'aging'));
	var totals   = @json(array_map(function($r){ return (float)($r['inv_total'] ?? 0); }, $data));
	var paid     = @json(array_map(function($r){ return (float)($r['inv_paid']  ?? 0); }, $data));
	var owing    = @json(array_map(function($r){ return (float)($r['inv_owing'] ?? 0); }, $data));
	var totalLbl = @json($LANG['total'] ?? 'Total');
	var paidLbl  = @json($LANG['paid']  ?? 'Paid');
	var owingLbl = @json($LANG['owing'] ?? 'Owing');

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var primary   = cssVar('--tblr-primary')       || '#45aaf2';
		var success   = cssVar('--tblr-success')       || '#2fb344';
		var danger    = cssVar('--tblr-danger')        || '#e03131';
		var bodyColor = cssVar('--tblr-body-color')    || (isDark ? '#c8d3e1' : '#1d273b');
		var borderCol = cssVar('--tblr-border-color')  || (isDark ? '#3d4555' : '#e6e7e9');
		return {
			chart: {
				type: 'bar', fontFamily: 'inherit', height: 260,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: [
				{ name: totalLbl, data: totals },
				{ name: paidLbl,  data: paid   },
				{ name: owingLbl, data: owing  }
			],
			xaxis: {
				categories: periods,
				labels: { style: { colors: bodyColor } },
				axisBorder: { color: borderCol }, axisTicks: { color: borderCol }
			},
			yaxis: { labels: { style: { colors: bodyColor }, formatter: function(v){ return v.toLocaleString(); } } },
			colors: [primary, success, danger],
			plotOptions: { bar: { borderRadius: 3, columnWidth: '70%', grouped: true } },
			dataLabels: { enabled: false },
			legend: { position: 'bottom', labels: { colors: bodyColor } },
			grid: { borderColor: borderCol, strokeDashArray: 4 },
			tooltip: { theme: isDark ? 'dark' : 'light', y: { formatter: function(v){ return v.toLocaleString(); } } }
		};
	}

	function initChart() {
		var chart = new ApexCharts(document.getElementById('chart-aging-total'), buildOptions());
		chart.render();
		document.documentElement.addEventListener('si-theme-changed', function () {
			chart.updateOptions(buildOptions(), true, true);
		});
	}

	if (typeof ApexCharts !== 'undefined') { initChart(); }
	else {
		var s = document.createElement('script');
		s.src = 'https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js';
		s.onload = initChart; document.head.appendChild(s);
	}
})();
</script>
@endif
