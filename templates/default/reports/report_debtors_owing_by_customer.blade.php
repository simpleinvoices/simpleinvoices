@php
	$data = $data ?? [];
	$chart_data = $report_chart_data ?? $data;
	$rg = $report_chart_guard ?? ['enabled' => true];
	$total_owed = $total_owed ?? 0;
	$customer_count = count($data);
	$chartHeight = max(200, min(480, count($chart_data) * 36 + 60));
	$showChart = count($chart_data) > 0 && !empty($rg['enabled']);
@endphp

<div class="card">

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

	@if($showChart)
	@include('templates.default.reports.chart_truncation_notice')
	{{-- Chart: horizontal bar of customer debts --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-owing-customer" style="min-height:{{ $chartHeight }}px;"></div>
	</div>
	@endif

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
					<td class="text-end">{!! CurrencySignHelper::format($customer['inv_total'] ?? 0, $customer['currency_sign'] ?? '', '', $customer['currency_code'] ?? '') !!}</td>
					<td class="text-end text-secondary">{!! CurrencySignHelper::format($customer['inv_paid'] ?? 0, $customer['currency_sign'] ?? '', '', $customer['currency_code'] ?? '') !!}</td>
					<td class="text-end fw-bold text-red">{!! CurrencySignHelper::format($customer['inv_owing'] ?? 0, $customer['currency_sign'] ?? '', '', $customer['currency_code'] ?? '') !!}@if(!empty($customer['currency_code'])) <span class="badge bg-secondary-lt text-muted ms-1" style="font-size:.7rem">{{ $customer['currency_code'] }}</span>@endif</td>
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

@if($showChart)
<script>
(function () {
	var labels  = @json(array_column($chart_data, 'customer'));
	var amounts = @json(array_map(function($r){ return (float)($r['inv_owing'] ?? 0); }, $chart_data));
	var owingLbl = @json($LANG['owing'] ?? 'Owing');
	var chartH   = {{ $chartHeight }};

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var danger    = cssVar('--tblr-danger')        || '#e03131';
		var bodyColor = cssVar('--tblr-body-color')    || (isDark ? '#c8d3e1' : '#1d273b');
		var borderCol = cssVar('--tblr-border-color')  || (isDark ? '#3d4555' : '#e6e7e9');
		return {
			chart: {
				type: 'bar', fontFamily: 'inherit', height: chartH,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: [{ name: owingLbl, data: amounts }],
			xaxis: {
				categories: labels,
				labels: { style: { colors: bodyColor } },
				axisBorder: { color: borderCol }, axisTicks: { color: borderCol }
			},
			yaxis: { labels: { style: { colors: bodyColor }, formatter: function(v){ return v.toLocaleString(); } } },
			colors: [danger],
			plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '65%' } },
			dataLabels: { enabled: false },
			grid: { borderColor: borderCol, strokeDashArray: 4 },
			tooltip: { theme: isDark ? 'dark' : 'light', y: { formatter: function(v){ return v.toLocaleString(); } } }
		};
	}

	function initChart() {
		var chart = new ApexCharts(document.getElementById('chart-owing-customer'), buildOptions());
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
