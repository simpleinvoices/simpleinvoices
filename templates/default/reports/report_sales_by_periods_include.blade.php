@php
	$chartId    = $chartId    ?? ('chart-periods-' . uniqid());
	$chartColor = $chartColor ?? 'primary';
	$years      = $years ?? [];
	$chart_js_years = $chart_js_years ?? $years;
	$rg         = $report_chart_guard ?? ['enabled' => true];
	$hasData    = !empty($this_data['months']) && !empty($years);
	$showChart  = $hasData && !empty($rg['enabled']) && !empty($chart_js_years);
	$summary_currency_sign = $summary_currency_sign ?? '';

	// Build JS-ready month labels and per-year series
	// months: [ '01' => [ year => amount ], ... ]
	$months_data = $this_data['months'] ?? [];
	$month_keys  = array_keys($months_data);          // ['01','02',...]
@endphp

@if($showChart)
{{-- Line chart: months on x, one line per year --}}
<div id="{{ $chartId }}" class="mb-3"></div>
@endif

<div class="table-responsive">
<table class="table table-sm table-vcenter table-hover si_report_table mb-0">
	<thead>
		<tr>
			<th></th>
		@foreach(($years ?? []) as $year)
			<th class="text-end fw-bold">{{ $year ?? '' }}</th>
@if($show_rates ?? false)
			<th class="rate text-end text-secondary small">%</th>
@endif
		@endforeach
		</tr>
	</thead>

	<tfoot>
		<tr class="fw-bold table-active">
			<th>{{ $LANG['total'] ?? '' }}</th>
		@foreach(($years ?? []) as $year)
			<td class="text-end">{{ ($summary_currency_sign)|si_currency_display }}{{ siLocal::number($this_data['total'][$year] ?? 0) ?: '-' }}</td>
@if($show_rates ?? false)
			@php $rate = $this_data['total_rate'][$year] ?? 0; @endphp
			<td class="rate text-end small">
				@if($rate)
				<span class="badge {{ $rate < 0 ? 'bg-red-lt text-red' : 'bg-green-lt text-green' }}">
					{{ $rate > 0 ? '+' : '' }}{{ siLocal::number($rate) }}%
				</span>
				@endif
			</td>
@endif
		@endforeach
		</tr>
	</tfoot>

	<tbody>
	@foreach(($this_data['months'] ?? []) as $month => $amount)
		<tr>
			<th class="text-secondary">{{ ucfirst(siLocal::date('2000-' . $month . '-01', 'month')) }}</th>
		@foreach(($years ?? []) as $year)
			<td class="text-end">{{ ($summary_currency_sign)|si_currency_display }}{{ siLocal::number($amount[$year] ?? 0) ?: '-' }}</td>
@if($show_rates ?? false)
			@php $mrate = $this_data['months_rate'][$month][$year] ?? 0; @endphp
			<td class="rate text-end small">
				@if($mrate)
				<span class="badge {{ $mrate < 0 ? 'bg-red-lt text-red' : 'bg-green-lt text-green' }}">
					{{ $mrate > 0 ? '+' : '' }}{{ siLocal::number($mrate) }}%
				</span>
				@endif
			</td>
@endif
		@endforeach
		</tr>
	@endforeach
	</tbody>
</table>
</div>

@if($showChart)
<script>
(function () {
	var monthKeys   = @json($month_keys);
	var monthsData  = @json($months_data);
	var years       = @json($chart_js_years);
	var chartId     = @json($chartId);
	var chartColor  = @json($chartColor);
	var totalData   = @json($this_data['total'] ?? []);

	// Month abbreviations (Jan–Dec) from numeric key
	var monthNames = monthKeys.map(function(k) {
		var d = new Date(2000, parseInt(k, 10) - 1, 1);
		return d.toLocaleString(undefined, { month: 'short' });
	});

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	var lineColours = ['#2fb344','#45aaf2','#f76707','#ae3ec9','#f59f00','#0ca678','#e03131','#4263eb'];

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var accentVar = '--tblr-' + chartColor;
		var accent    = cssVar(accentVar) || (chartColor === 'success' ? '#2fb344' : '#45aaf2');
		var bodyColor = cssVar('--tblr-body-color')   || (isDark ? '#c8d3e1' : '#1d273b');
		var borderCol = cssVar('--tblr-border-color') || (isDark ? '#3d4555' : '#e6e7e9');

		var series = years.map(function(yr, idx) {
			var ydata = monthKeys.map(function(mk) {
				return (monthsData[mk] && monthsData[mk][yr]) ? parseFloat(monthsData[mk][yr]) : 0;
			});
			return { name: String(yr), data: ydata };
		});

		// Use distinct colours - accent for first year, then rotate
		var colours = years.map(function(yr, idx) {
			return lineColours[idx % lineColours.length];
		});
		colours[0] = accent;

		return {
			chart: {
				type: 'area', fontFamily: 'inherit', height: 220,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: series,
			xaxis: {
				categories: monthNames,
				labels: { style: { colors: bodyColor }, rotate: 0 },
				axisBorder: { color: borderCol }, axisTicks: { color: borderCol }
			},
			yaxis: { labels: { style: { colors: bodyColor }, formatter: function(v){ return v.toLocaleString(); } } },
			colors: colours,
			stroke: { width: 2, curve: 'smooth' },
			fill: { type: 'gradient', gradient: { opacityFrom: 0.12, opacityTo: 0.01 } },
			legend: { position: 'bottom', labels: { colors: bodyColor } },
			dataLabels: { enabled: false },
			grid: { borderColor: borderCol, strokeDashArray: 4 },
			tooltip: { theme: isDark ? 'dark' : 'light', y: { formatter: function(v){ return v.toLocaleString(); } } }
		};
	}

	function initChart() {
		var el = document.getElementById(chartId);
		if (!el) return;
		var chart = new ApexCharts(el, buildOptions());
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
