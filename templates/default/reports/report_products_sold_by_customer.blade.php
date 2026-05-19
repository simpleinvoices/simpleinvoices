@php
	$data = $data ?? [];
	$rg = $report_chart_guard ?? ['enabled' => true];
	$omitCap = !empty($rg['chart_omitted_invoice_cap']);

	$total_qty_all = array_sum(array_column($data, 'total_quantity'));
	$customer_count = count($data);

	$chart_rows = $report_chart_rows ?? [];
	$series_names = $report_chart_series_names ?? [];
	if (!$omitCap && count($chart_rows) === 0) {
		$chart_rows = array_values($data);
		$series_names = [];
		foreach ($data as $customer) {
			foreach (($customer['products'] ?? []) as $p) {
				$desc = $p['description'] ?? '';
				if ($desc !== '' && ! in_array($desc, $series_names, true)) {
					$series_names[] = $desc;
				}
			}
		}
	}

	$chart_labels = array_column($chart_rows, 'name');
	$chart_series = [];
	foreach ($series_names as $prod_name) {
		$row = ['name' => $prod_name, 'data' => []];
		foreach ($chart_rows as $customer) {
			$qty = 0;
			foreach (($customer['products'] ?? []) as $p) {
				if (($p['description'] ?? '') === $prod_name) {
					$qty = (float) ($p['sum_quantity'] ?? 0);
					break;
				}
			}
			$row['data'][] = $qty;
		}
		$chart_series[] = $row;
	}

	$chart_row_n = count($chart_rows);
	$chartHeight = max(220, min(520, $chart_row_n * 46 + 80));
	$hasChartData = $chart_row_n > 0 && count($series_names) > 0;
	$showChart = !$omitCap && $hasChartData && ! empty($rg['enabled']);
@endphp

<div class="card">

	{{-- Summary stats --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-4">
				<div class="p-3 bg-cyan-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total'] ?? 'Total' }} {{ $LANG['quantity'] ?? 'Qty' }}</div>
					<div class="h2 fw-bold text-cyan mb-0">{{ siLocal::number($total_qty_all) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['customers'] ?? 'Customers' }}</div>
					<div class="h2 fw-bold text-blue mb-0">{{ $customer_count }}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="p-3 bg-indigo-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['products'] ?? 'Products' }}</div>
					<div class="h2 fw-bold text-indigo mb-0">{{ (int)($rg['chart_series_total'] ?? count($series_names)) }}</div>
				</div>
			</div>
		</div>
	</div>

	@if($omitCap && $customer_count > 0)
	@include('templates.default.reports.chart_omitted_invoice_cap')
	@elseif($showChart)
	@include('templates.default.reports.chart_truncation_notice')
	{{-- Stacked horizontal bar: each bar = customer, segments = products --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-products-by-customer" style="min-height:{{ $chartHeight }}px;"></div>
	</div>
	@endif

	{{-- Detail tables grouped by customer --}}
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

@if($showChart)
<script>
(function () {
	var series    = @json($chart_series);
	var labels    = @json($chart_labels);
	var qtyLbl    = @json($LANG['quantity'] ?? 'Qty');
	var chartH    = {{ $chartHeight }};
	var palette   = [
		'#17a2b8','#2fb344','#f59f00','#4263eb','#f76707',
		'#ae3ec9','#0ca678','#e03131','#fd7e14','#20c997',
		'#45aaf2','#7048e8','#e83e8c','#6c757d','#343a40'
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
		var chart = new ApexCharts(document.getElementById('chart-products-by-customer'), buildOptions());
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
