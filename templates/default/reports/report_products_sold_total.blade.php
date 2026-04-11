@php
	$data = $data ?? [];
	$total_quantity = $total_quantity ?? 0;
	$product_count = count($data);
	$chartHeight = max(200, min(420, $product_count * 38 + 60));
@endphp

<div class="card">

	{{-- Summary stats --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-6">
				<div class="p-3 bg-cyan-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total'] ?? '' }}</div>
					<div class="h2 fw-bold text-cyan mb-0">{{ siLocal::number($total_quantity) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['products'] ?? 'Products' }}</div>
					<div class="h2 fw-bold text-blue mb-0">{{ $product_count }}</div>
				</div>
			</div>
		</div>
	</div>

	@if(count($data) > 0)
	{{-- Chart: horizontal bar —— product vs qty --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-products-total" style="min-height:{{ $chartHeight }}px;"></div>
	</div>
	@endif

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>#</th>
					<th>{{ $LANG['product'] ?? 'Product' }}</th>
					<th class="text-end">{{ $LANG['quantity'] ?? 'Qty' }}</th>
					<th class="w-25 d-none d-md-table-cell"></th>
				</tr>
			</thead>
			<tbody>
			@foreach($data as $i => $customer)
				@php $pct = $total_quantity > 0 ? round(($customer['sum_quantity'] / $total_quantity) * 100) : 0; @endphp
				<tr>
					<td class="text-secondary">{{ $i + 1 }}</td>
					<td class="fw-medium">{{ $customer['description'] ?? '' }}</td>
					<td class="text-end fw-semibold">{{ siLocal::number($customer['sum_quantity'] ?? 0) ?: '-' }}</td>
					<td class="d-none d-md-table-cell">
						<div class="d-flex align-items-center gap-2">
							<div class="progress flex-grow-1" style="height:6px;">
								<div class="progress-bar bg-cyan" style="width:{{ $pct }}%"></div>
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
					<td class="text-secondary">{{ $LANG['total'] ?? '' }}</td>
					<td class="text-end text-cyan">{{ siLocal::number($total_quantity) ?: '-' }}</td>
					<td class="d-none d-md-table-cell"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@if(count($data) > 0)
<script>
(function () {
	var labels     = @json(array_column($data, 'description'));
	var quantities = @json(array_map(function($r){ return (float)($r['sum_quantity'] ?? 0); }, $data));
	var qtyLbl     = @json($LANG['quantity'] ?? 'Qty');
	var chartH     = {{ $chartHeight }};

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var cyan      = cssVar('--tblr-cyan')          || '#17a2b8';
		var bodyColor = cssVar('--tblr-body-color')    || (isDark ? '#c8d3e1' : '#1d273b');
		var borderCol = cssVar('--tblr-border-color')  || (isDark ? '#3d4555' : '#e6e7e9');
		return {
			chart: {
				type: 'bar', fontFamily: 'inherit', height: chartH,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: [{ name: qtyLbl, data: quantities }],
			xaxis: {
				categories: labels,
				labels: { style: { colors: bodyColor } },
				axisBorder: { color: borderCol }, axisTicks: { color: borderCol }
			},
			yaxis: { labels: { style: { colors: bodyColor }, formatter: function(v){ return v.toLocaleString(); } } },
			colors: [cyan],
			plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '65%' } },
			dataLabels: { enabled: false },
			grid: { borderColor: borderCol, strokeDashArray: 4 },
			tooltip: { theme: isDark ? 'dark' : 'light', y: { formatter: function(v){ return v.toLocaleString(); } } }
		};
	}

	function initChart() {
		var chart = new ApexCharts(document.getElementById('chart-products-total'), buildOptions());
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
