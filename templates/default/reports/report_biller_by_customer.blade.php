@php
	$data        = $data ?? [];
	$total_sales = $total_sales ?? 0;
	$biller_count = count($data);

	// ── Chart data prep ──────────────────────────────────────────────────────
	// Collect all unique customer names
	$all_customers = [];
	foreach ($data as $biller) {
		foreach (($biller['customers'] ?? []) as $c) {
			$name = $c['name'] ?? '';
			if ($name !== '' && !in_array($name, $all_customers, true)) {
				$all_customers[] = $name;
			}
		}
	}

	// Biller name labels for y-axis
	$chart_labels = array_values(array_column($data, 'name'));

	// One series per customer: data[i] = sales amount for biller i
	$chart_series = [];
	foreach ($all_customers as $cust_name) {
		$row = ['name' => $cust_name, 'data' => []];
		foreach ($data as $biller) {
			$total = 0;
			foreach (($biller['customers'] ?? []) as $c) {
				if (($c['name'] ?? '') === $cust_name) {
					$total = (float)($c['sum_total'] ?? 0);
					break;
				}
			}
			$row['data'][] = $total;
		}
		$chart_series[] = $row;
	}

	$chartHeight = max(220, min(520, $biller_count * 46 + 80));
	$hasChartData = $biller_count > 0 && count($all_customers) > 0;
@endphp

<div class="card">

	{{-- Summary stats --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-4">
				<div class="p-3 bg-indigo-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total_sales'] ?? '' }}</div>
					<div class="h2 fw-bold text-indigo mb-0">{{ siLocal::number($total_sales) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['billers'] ?? 'Billers' }}</div>
					<div class="h2 fw-bold text-blue mb-0">{{ $biller_count }}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="p-3 bg-cyan-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['customers'] ?? 'Customers' }}</div>
					<div class="h2 fw-bold text-cyan mb-0">{{ count($all_customers) }}</div>
				</div>
			</div>
		</div>
	</div>

	@if($hasChartData)
	{{-- Stacked horizontal bar: each bar = biller, segments = customers --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-biller-by-customer" style="min-height:{{ $chartHeight }}px;"></div>
	</div>
	@endif

	{{-- Detail tables grouped by biller --}}
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

@if($hasChartData)
<script>
(function () {
	var series    = @json($chart_series);
	var labels    = @json($chart_labels);
	var salesLbl  = @json($LANG['sales'] ?? 'Sales');
	var chartH    = {{ $chartHeight }};
	var palette   = [
		'#45aaf2','#2fb344','#f59f00','#4263eb','#f76707',
		'#ae3ec9','#0ca678','#e03131','#17a2b8','#fd7e14',
		'#20c997','#7048e8','#e83e8c','#6c757d','#343a40'
	];

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var bodyColor = cssVar('--tblr-body-color')   || (isDark ? '#c8d3e1' : '#1d273b');
		var borderCol = cssVar('--tblr-border-color') || (isDark ? '#3d4555' : '#e6e7e9');
		return {
			chart: {
				type: 'bar', fontFamily: 'inherit', height: chartH,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent', stacked: true
			},
			series: series,
			xaxis: {
				labels: { style: { colors: bodyColor }, formatter: function(v){ return v.toLocaleString(); } },
				axisBorder: { color: borderCol }, axisTicks: { color: borderCol }
			},
			yaxis: {
				categories: labels,
				labels: { style: { colors: bodyColor } }
			},
			colors: palette,
			plotOptions: { bar: { horizontal: true, borderRadius: 3, barHeight: '65%' } },
			dataLabels: { enabled: false },
			legend: {
				position: 'bottom',
				labels: { colors: bodyColor },
				itemMargin: { horizontal: 6, vertical: 2 }
			},
			grid: { borderColor: borderCol, strokeDashArray: 4 },
			tooltip: {
				theme: isDark ? 'dark' : 'light',
				y: { formatter: function(v){ return v.toLocaleString(); } }
			}
		};
	}

	function initChart() {
		var chart = new ApexCharts(document.getElementById('chart-biller-by-customer'), buildOptions());
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
