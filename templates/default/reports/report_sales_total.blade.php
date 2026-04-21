@php
	$data = $data ?? [];
	$chartData = $report_chart_data ?? $data;
	$rg = $report_chart_guard ?? ['enabled' => true];
	$grand_total_sales = $grand_total_sales ?? 0;
	$invoice_count = array_sum(array_column($data, 'count'));
	$showChart = count($chartData) > 0 && !empty($rg['enabled']);
@endphp

<div class="card">

	{{-- Summary stats --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-6">
				<div class="p-3 bg-green-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total_sales'] ?? '' }}</div>
					<div class="h2 fw-bold text-green mb-0">{{ siLocal::number($grand_total_sales) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['invoices'] ?? '' }}</div>
					<div class="h2 fw-bold text-blue mb-0">{{ $invoice_count }}</div>
				</div>
			</div>
		</div>
	</div>

	@if($showChart)
	@include('templates.default.reports.chart_truncation_notice')
	{{-- Chart --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-sales-total"></div>
	</div>
	@endif

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>{{ $LANG['invoice_preferences'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['invoices'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total_sales'] ?? '' }}</th>
					<th class="w-25 d-none d-md-table-cell"></th>
				</tr>
			</thead>
			<tbody>
			@foreach($data as $total_sales)
				@php $pct = $grand_total_sales > 0 ? round(($total_sales['sum_total'] / $grand_total_sales) * 100) : 0; @endphp
				<tr>
					<td class="fw-medium">{{ $total_sales['template'] ?? '' }}</td>
					<td class="text-end text-secondary">{{ siLocal::number($total_sales['count'] ?? 0) ?: '-' }}</td>
					<td class="text-end fw-semibold">{{ ($total_sales['currency_sign'] ?? '')|si_currency_display }}{{ siLocal::number($total_sales['sum_total'] ?? 0) ?: '-' }}@if(!empty($total_sales['currency_code'])) <span class="badge bg-secondary-lt text-muted ms-1" style="font-size:.7rem">{{ $total_sales['currency_code'] }}</span>@endif</td>
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
					<td colspan="2" class="text-end text-secondary">{{ $LANG['total_sales'] ?? '' }}</td>
					<td class="text-end text-green">{{ siLocal::number($grand_total_sales) ?: '-' }}</td>
					<td class="d-none d-md-table-cell"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@if($showChart)
<script>
(function () {
	var labels  = @json(array_column($chartData, 'template'));
	var amounts = @json(array_map(function($r){ return (float)($r['sum_total'] ?? 0); }, $chartData));
	var counts  = @json(array_map(function($r){ return (int)($r['count'] ?? 0); }, $chartData));
	var salesLbl = @json($LANG['total_sales'] ?? 'Total Sales');
	var invLbl   = @json($LANG['invoices'] ?? 'Invoices');

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var success   = cssVar('--tblr-success')      || '#2fb344';
		var bodyColor = cssVar('--tblr-body-color')   || (isDark ? '#c8d3e1' : '#1d273b');
		var borderCol = cssVar('--tblr-border-color') || (isDark ? '#3d4555' : '#e6e7e9');
		return {
			chart: {
				type: 'bar', fontFamily: 'inherit', height: 240,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: [{ name: salesLbl, data: amounts }],
			xaxis: {
				categories: labels,
				labels: { style: { colors: bodyColor } },
				axisBorder: { color: borderCol }, axisTicks: { color: borderCol }
			},
			yaxis: {
				labels: { style: { colors: bodyColor }, formatter: function(v){ return v.toLocaleString(); } }
			},
			colors: [success],
			plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
			dataLabels: { enabled: false },
			grid: { borderColor: borderCol, strokeDashArray: 4 },
			tooltip: {
				theme: isDark ? 'dark' : 'light',
				y: { formatter: function(v){ return v.toLocaleString(); } }
			}
		};
	}

	function initChart() {
		var chart = new ApexCharts(document.getElementById('chart-sales-total'), buildOptions());
		chart.render();
		document.documentElement.addEventListener('si-theme-changed', function () {
			chart.updateOptions(buildOptions(), true, true);
		});
	}

	if (typeof ApexCharts !== 'undefined') { initChart(); }
	else {
		var s = document.createElement('script');
		s.src = 'https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js';
		s.onload = initChart;
		document.head.appendChild(s);
	}
})();
</script>
@endif
