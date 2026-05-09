@php
	$data = $data ?? [];
	$total_owed = $total_owed ?? 0;

	$aging_colors = [
		'0-14'  => ['bg' => 'bg-success-lt', 'text' => 'text-success',  'bar' => 'bg-success'],
		'15-30' => ['bg' => 'bg-yellow-lt',  'text' => 'text-yellow',   'bar' => 'bg-yellow'],
		'31-60' => ['bg' => 'bg-orange-lt',  'text' => 'text-orange',   'bar' => 'bg-orange'],
		'61-90' => ['bg' => 'bg-red-lt',     'text' => 'text-red',      'bar' => 'bg-red'],
		'90+'   => ['bg' => 'bg-dark',       'text' => 'text-white',    'bar' => 'bg-dark'],
	];

	// Build donut data (only non-zero buckets)
	$donut_labels  = [];
	$donut_amounts = [];
	foreach($data as $bucket => $period) {
		$amt = $period['sum_total'] ?? 0;
		if($amt > 0) { $donut_labels[] = $bucket; $donut_amounts[] = (float)$amt; }
	}
@endphp

<div class="card">

	{{-- Summary stat + chart side by side on md+ --}}
	<div class="card-body border-bottom">
		<div class="row g-3 align-items-center">
			<div class="col-md-5">
				<div class="row g-3">
					<div class="col-12">
						<div class="p-3 bg-red-lt rounded-2 text-center">
							<div class="text-secondary small mb-1">{{ $LANG['total_owed'] ?? '' }}</div>
							<div class="h2 fw-bold text-red mb-0">{{ siLocal::number($total_owed) ?: '-' }}</div>
						</div>
					</div>
					@foreach($data as $bucket => $period)
					@php $c = $aging_colors[$bucket] ?? ['bg' => 'bg-secondary-lt', 'text' => 'text-secondary']; @endphp
					<div class="col-6">
						<div class="p-2 {{ $c['bg'] }} rounded-2 text-center">
							<div class="text-secondary small">{{ $bucket }}</div>
							<div class="fw-bold {{ $c['text'] }}">{{ siLocal::number($period['sum_total'] ?? 0) ?: '-' }}</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			@if(count($donut_amounts) > 0)
			<div class="col-md-7">
				<div id="chart-aging-donut"></div>
			</div>
			@endif
		</div>
	</div>

	@foreach($data as $bucket => $period)
	@php $c = $aging_colors[$bucket] ?? ['bg' => 'bg-secondary-lt', 'text' => 'text-secondary', 'bar' => 'bg-secondary']; @endphp

	<div class="card-body pb-0 pt-3 border-bottom">
		<div class="d-flex align-items-center mb-2">
			<span class="badge {{ $c['bg'] }} {{ $c['text'] }} me-2 fs-6">
				<i class="ti ti-clock me-1"></i>{{ $LANG['aging'] ?? '' }}: {{ $period['name'] ?? $bucket }}
			</span>
			<span class="text-secondary small">
				{{ count($period['invoices'] ?? []) }} {{ $LANG['invoices'] ?? '' }}
				&mdash; {{ $LANG['total'] ?? '' }}: <strong>{{ siLocal::number($period['sum_total'] ?? 0) ?: '-' }}</strong>
			</span>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-sm table-vcenter table-hover card-table mb-0">
			<thead>
				<tr>
					<th>{{ $LANG['invoice_id'] ?? '' }}</th>
					<th>{{ $LANG['invoice'] ?? '' }}</th>
					<th>{{ $LANG['biller'] ?? '' }}</th>
					<th>{{ $LANG['customer'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['paid'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['owing'] ?? '' }}</th>
					<th>{{ ucfirst($LANG['date'] ?? '') }}</th>
					<th class="text-end">{{ $LANG['age'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach(($period['invoices'] ?? []) as $invoice)
				<tr>
					<td class="text-secondary">{{ $invoice['id'] ?? '' }}</td>
					<td class="fw-medium">
						{{ $invoice['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }}
						{{ $invoice['index_id'] ?? '' }}
					</td>
					<td>{{ $invoice['biller'] ?? '' }}</td>
					<td>{{ $invoice['customer'] ?? '' }}</td>
					<td class="text-end">{!! CurrencySignHelper::format($invoice['inv_total'] ?? 0, $invoice['currency_sign'] ?? '', '', $invoice['denorm_currency_code'] ?? '') !!}</td>
					<td class="text-end text-secondary">{!! CurrencySignHelper::format($invoice['inv_paid'] ?? 0, $invoice['currency_sign'] ?? '', '', $invoice['denorm_currency_code'] ?? '') !!}</td>
					<td class="text-end fw-bold {{ $c['text'] }}">{!! CurrencySignHelper::format($invoice['inv_owing'] ?? 0, $invoice['currency_sign'] ?? '', '', $invoice['denorm_currency_code'] ?? '') !!}</td>
					<td class="text-secondary">{{ $invoice['date'] ?? '' }}</td>
					<td class="text-end">
						<span class="badge {{ $c['bg'] }} {{ $c['text'] }}">{{ $invoice['age'] ?? '' }}d</span>
					</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold table-active">
					<td colspan="6" class="text-secondary">{{ $LANG['total'] ?? '' }}</td>
					<td class="text-end {{ $c['text'] }}">{{ siLocal::number($period['sum_total'] ?? 0) ?: '-' }}</td>
					<td colspan="2"></td>
				</tr>
			</tfoot>
		</table>
	</div>
	@endforeach

	@if(empty($data))
	<div class="card-body text-center text-secondary py-5">
		<i class="ti ti-clock-off fs-1 d-block mb-2 text-success"></i>
		<div class="fw-semibold">{{ $LANG['no_outstanding_invoices'] ?? 'No outstanding invoices.' }}</div>
	</div>
	@endif
</div>

@if(count($donut_amounts) > 0)
<script>
(function () {
	var labels   = @json($donut_labels);
	var amounts  = @json($donut_amounts);
	var owedLbl  = @json($LANG['total_owed'] ?? 'Total Owed');
	// Colours in bucket order: 0-14 green, 15-30 yellow, 31-60 orange, 61-90 red, 90+ dark
	var colourMap = { '0-14': '#2fb344', '15-30': '#f59f00', '31-60': '#f76707', '61-90': '#e03131', '90+': '#1d273b' };
	var colours = labels.map(function(l){ return colourMap[l] || '#45aaf2'; });

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var bodyColor = cssVar('--tblr-body-color')   || (isDark ? '#c8d3e1' : '#1d273b');
		return {
			chart: {
				type: 'donut', fontFamily: 'inherit', height: 260,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: amounts,
			labels: labels,
			colors: colours,
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
						show: true, label: owedLbl, color: bodyColor,
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
		var chart = new ApexCharts(document.getElementById('chart-aging-donut'), buildOptions());
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
