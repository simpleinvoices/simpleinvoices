@php
	$data = $data ?? [];
	$rg = $report_chart_guard ?? ['enabled' => true];
	$omitCap = !empty($rg['chart_omitted_invoice_cap']);
	$total_sales = $total_sales ?? 0;
	$biller_count = count($data);

	$chart_billers = $report_chart_billers ?? [];
	$series_names = $report_chart_series_names ?? [];
	if (!$omitCap && count($chart_billers) === 0) {
		$chart_billers = array_values($data);
		$series_names = [];
		foreach ($data as $biller) {
			foreach (($biller['customers'] ?? []) as $c) {
				$name = $c['name'] ?? '';
				if ($name !== '' && ! in_array($name, $series_names, true)) {
					$series_names[] = $name;
				}
			}
		}
	}

	$chart_labels = array_column($chart_billers, 'name');
	$chart_series = [];
	foreach ($series_names as $cust_name) {
		$row = ['name' => $cust_name, 'data' => []];
		foreach ($chart_billers as $biller) {
			$total = 0;
			foreach (($biller['customers'] ?? []) as $c) {
				if (($c['name'] ?? '') === $cust_name) {
					$total = (float) ($c['sum_total'] ?? 0);
					break;
				}
			}
			$row['data'][] = $total;
		}
		$chart_series[] = $row;
	}

	$chart_biller_n = count($chart_billers);
	$chartHeight = max(220, min(520, $chart_biller_n * 46 + 80));
	$hasChartData = $chart_biller_n > 0 && count($series_names) > 0;
	$showChart = !$omitCap && $hasChartData && ! empty($rg['enabled']);
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
					<div class="h2 fw-bold text-cyan mb-0">{{ (int)($rg['chart_series_total'] ?? count($series_names)) }}</div>
				</div>
			</div>
		</div>
	</div>

	@if($omitCap && $biller_count > 0)
	@include('templates.default.reports.chart_omitted_invoice_cap')
	@elseif($showChart)
	@include('templates.default.reports.chart_truncation_notice')
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
						<td class="text-end fw-semibold">{!! CurrencySignHelper::format($customer['sum_total'] ?? 0, $customer['currency_sign'] ?? '', '', $customer['currency_code'] ?? '') !!}@if(!empty($customer['currency_code'])) <span class="badge bg-secondary-lt text-muted ms-1" style="font-size:.7rem">{{ $customer['currency_code'] }}</span>@endif</td>
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

@if($showChart)
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
		s.src = './templates/default/vendor/apexcharts/apexcharts.min.js';
		s.onload = initChart; document.head.appendChild(s);
	}
})();
</script>
@endif
