@php
	$data = $data ?? [];
	$total_owed = $total_owed ?? 0;
	$invoice_count = count($data);
	// Top 15 for chart (sorted by owing desc)
	$chart_data = $data;
	usort($chart_data, function($a,$b){ return ($b['inv_owing'] ?? 0) <=> ($a['inv_owing'] ?? 0); });
	$chart_data = array_slice($chart_data, 0, 15);
	$chartHeight = max(200, min(480, count($chart_data) * 36 + 60));
@endphp

<div class="card">

	{{-- Summary stats --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-6">
				<div class="p-3 bg-red-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total_owed'] ?? '' }}</div>
					<div class="h2 fw-bold text-red mb-0">{{ siLocal::number($total_owed) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="p-3 bg-orange-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['invoices'] ?? '' }}</div>
					<div class="h2 fw-bold text-orange mb-0">{{ $invoice_count }}</div>
				</div>
			</div>
		</div>
	</div>

	@if(count($chart_data) > 0)
	{{-- Chart: horizontal bar of top debtors --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-debtors-amount" style="min-height:{{ $chartHeight }}px;"></div>
	</div>
	@endif

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>{{ $LANG['invoice_id'] ?? '' }}</th>
					<th>{{ $LANG['invoice'] ?? '' }}</th>
					<th>{{ $LANG['biller'] ?? '' }}</th>
					<th>{{ $LANG['customer'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['paid'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['owing'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach($data as $invoice)
				<tr>
					<td class="text-secondary">{{ $invoice['id'] ?? '' }}</td>
					<td class="fw-medium">
						{{ $invoice['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }}
						{{ $invoice['index_id'] ?? '' }}
					</td>
					<td>{{ $invoice['biller'] ?? '' }}</td>
					<td>{{ $invoice['customer'] ?? '' }}</td>
					<td class="text-end">{{ siLocal::number($invoice['inv_total'] ?? 0) }}</td>
					<td class="text-end text-secondary">{{ siLocal::number($invoice['inv_paid'] ?? 0) }}</td>
					<td class="text-end fw-bold text-red">{{ siLocal::number($invoice['inv_owing'] ?? 0) }}</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold">
					<td colspan="6" class="text-end text-secondary">{{ $LANG['total_owed'] ?? '' }}</td>
					<td class="text-end text-red">{{ siLocal::number($total_owed) ?: '-' }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@if(count($chart_data) > 0)
<script>
(function () {
	var chartRows = @json(array_map(function($r){
		return [
			($r['pref_inv_wording'] ?? 'Inv') . ' ' . ($r['index_id'] ?? $r['id'] ?? ''),
			(float)($r['inv_owing'] ?? 0)
		];
	}, $chart_data));
	var labels  = chartRows.map(function(r){ return r[0]; });
	var amounts = chartRows.map(function(r){ return r[1]; });
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
		var chart = new ApexCharts(document.getElementById('chart-debtors-amount'), buildOptions());
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
