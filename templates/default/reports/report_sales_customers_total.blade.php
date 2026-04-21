@php
	$data = $data ?? [];
	$rg = $report_chart_guard ?? ['enabled' => true];
	$omitCap = !empty($rg['chart_omitted_invoice_cap']);
	$chartData = $omitCap ? [] : ($report_chart_data ?? $data);
	$total_sales = $total_sales ?? 0;
	$customer_count = count($data);
	$showChart = !$omitCap && count($chartData) > 0 && !empty($rg['enabled']);
@endphp

<div class="card">

	{{-- Summary stats --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-6">
				<div class="p-3 bg-green-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total_sales'] ?? '' }}</div>
					<div class="h2 fw-bold text-green mb-0">{{ siLocal::number($total_sales) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['customers'] ?? 'Customers' }}</div>
					<div class="h2 fw-bold text-blue mb-0">{{ $customer_count }}</div>
				</div>
			</div>
		</div>
	</div>

	@if($omitCap && $customer_count > 0)
	@include('templates.default.reports.chart_omitted_invoice_cap')
	@elseif($showChart)
	@include('templates.default.reports.chart_truncation_notice')
	{{-- Chart: donut of sales share by customer --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-sales-customers"></div>
	</div>
	@endif

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>#</th>
					<th>{{ $LANG['customer'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total_sales'] ?? '' }}</th>
					<th class="w-25 d-none d-md-table-cell"></th>
				</tr>
			</thead>
			<tbody>
			@foreach($data as $i => $customer)
				@php $pct = $total_sales > 0 ? round(($customer['sum_total'] / $total_sales) * 100) : 0; @endphp
				<tr>
					<td class="text-secondary">{{ $i + 1 }}</td>
					<td class="fw-medium">{{ $customer['name'] ?? '' }}</td>
					<td class="text-end fw-semibold">{{ ($customer['currency_sign'] ?? '')|si_currency_display }}{{ siLocal::number($customer['sum_total'] ?? 0) ?: '-' }}@if(!empty($customer['currency_code'])) <span class="badge bg-secondary-lt text-muted ms-1" style="font-size:.7rem">{{ $customer['currency_code'] }}</span>@endif</td>
					<td class="d-none d-md-table-cell">
						<div class="d-flex align-items-center gap-2">
							<div class="progress flex-grow-1" style="height:6px;">
								<div class="progress-bar bg-green" style="width:{{ $pct }}%"></div>
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
					<td class="text-end text-green">{{ siLocal::number($total_sales) ?: '-' }}</td>
					<td class="d-none d-md-table-cell"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@if($showChart)
<script>
(function () {
	var labels  = @json(array_column($chartData, 'name'));
	var amounts = @json(array_map(function($r){ return (float)($r['sum_total'] ?? 0); }, $chartData));
	var salesLbl = @json($LANG['total_sales'] ?? 'Total Sales');
	var palette  = ['#45aaf2','#2fb344','#f59f00','#f76707','#e03131','#7048e8','#0ca678','#ae3ec9','#fd7e14','#20c997'];

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
			legend: {
				position: 'bottom',
				labels: { colors: bodyColor }
			},
			dataLabels: {
				enabled: true,
				formatter: function(val) { return val.toFixed(1) + '%'; },
				style: { fontSize: '11px' }
			},
			plotOptions: {
				pie: { donut: { size: '60%', labels: {
					show: true,
					total: {
						show: true,
						label: salesLbl,
						color: bodyColor,
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
		var chart = new ApexCharts(document.getElementById('chart-sales-customers'), buildOptions());
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
