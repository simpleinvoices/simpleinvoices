@php
	$data = $data ?? [];
	$total_sales = $total_sales ?? 0;
	$biller_count = count($data);
@endphp

<div class="card">

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

	@if(count($data) > 0)
	{{-- Chart: donut of sales by biller --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-biller-total"></div>
	</div>
	@endif

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

@if(count($data) > 0)
<script>
(function () {
	var labels  = @json(array_column($data, 'name'));
	var amounts = @json(array_map(function($r){ return (float)($r['sum_total'] ?? 0); }, $data));
	var salesLbl = @json($LANG['total_sales'] ?? 'Total Sales');
	var palette  = ['#4263eb','#45aaf2','#7048e8','#0ca678','#2fb344','#f59f00','#f76707','#ae3ec9'];

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var bodyColor = cssVar('--tblr-body-color')   || (isDark ? '#c8d3e1' : '#1d273b');
		var borderCol = cssVar('--tblr-border-color') || (isDark ? '#3d4555' : '#e6e7e9');
		return {
			chart: {
				type: 'donut', fontFamily: 'inherit', height: 280,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: amounts,
			labels: labels,
			colors: palette,
			legend: { position: 'bottom', labels: { colors: bodyColor } },
			dataLabels: {
				enabled: true,
				formatter: function(val) { return val.toFixed(1) + '%'; },
				style: { fontSize: '11px' }
			},
			plotOptions: {
				pie: { donut: { size: '60%', labels: {
					show: true,
					total: {
						show: true, label: salesLbl, color: bodyColor,
						formatter: function(w) {
							return w.globals.seriesTotals.reduce(function(a,b){return a+b;},0).toLocaleString();
						}
					},
					value: { color: bodyColor }
				}}}
			},
			tooltip: { theme: isDark ? 'dark' : 'light', y: { formatter: function(v){ return v.toLocaleString(); } } }
		};
	}

	function initChart() {
		var chart = new ApexCharts(document.getElementById('chart-biller-total'), buildOptions());
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
