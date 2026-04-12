<div class="card mb-4">
	<form name="frmpost" id="form_report_invoice_profit" action="index.php?module=reports&amp;view=report_invoice_profit" method="post">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-sm-5">
					<label class="form-label">{{ $LANG['start_date'] ?? '' }}</label>
					<div class="input-icon">
						<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
						<input type="text"
							class="form-control validate[required,custom[date],length[0,10]] date-picker"
							name="start_date" id="date1"
							value="{{ $start_date ?? '' }}" />
					</div>
				</div>
				<div class="col-sm-5">
					<label class="form-label">{{ $LANG['end_date'] ?? '' }}</label>
					<div class="input-icon">
						<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
						<input type="text"
							class="form-control validate[required,custom[date],length[0,10]] date-picker"
							name="end_date" id="date2"
							value="{{ $end_date ?? '' }}" />
					</div>
				</div>
				<div class="col-sm-2">
					<button type="submit" class="btn btn-primary w-100" name="submit" value="{{ $LANG['run_report'] ?? '' }}">
						<i class="ti ti-chart-bar me-1"></i>{{ $LANG['run_report'] ?? '' }}
					</button>
				</div>
			</div>
		</div>
	</form>
</div>

@if(!empty($invoices))
@php
	$rg = $report_chart_guard ?? ['enabled' => true];
	$chartInvoices = $report_chart_invoices ?? ($invoices ?? []);
	$showChart = count($chartInvoices) > 0 && !empty($rg['enabled']);
	$invoice_totals = $invoice_totals ?? [];
	$sum_total  = (float)($invoice_totals['sum_total']  ?? 0);
	$sum_cost   = (float)($invoice_totals['sum_cost']   ?? 0);
	$sum_profit = (float)($invoice_totals['sum_profit'] ?? 0);
	$margin_pct = $sum_total > 0 ? round(($sum_profit / $sum_total) * 100, 1) : 0;
@endphp

<div class="card">
	{{-- Summary stats --}}
	<div class="card-body border-bottom">
		<p class="text-secondary fw-medium mb-3">
			{{ strtr($LANG['profit_per_invoice_summary'] ?? '', ['{start_date}' => $start_date ?? '', '{end_date}' => $end_date ?? '']) }}
		</p>
		<div class="row g-3">
			<div class="col-sm-3">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total'] ?? '' }}</div>
					<div class="h3 fw-bold text-blue mb-0">{{ siLocal::number($sum_total) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="p-3 bg-orange-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['cost'] ?? '' }}</div>
					<div class="h3 fw-bold text-orange mb-0">{{ siLocal::number($sum_cost) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="p-3 {{ $sum_profit >= 0 ? 'bg-green-lt' : 'bg-red-lt' }} rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['profit'] ?? '' }}</div>
					<div class="h3 fw-bold {{ $sum_profit >= 0 ? 'text-green' : 'text-red' }} mb-0">
						{{ siLocal::number($sum_profit) ?: '-' }}
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="p-3 bg-purple-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['margin'] ?? 'Margin' }}</div>
					<div class="h3 fw-bold text-purple mb-0">{{ $margin_pct }}%</div>
				</div>
			</div>
		</div>
	</div>

	@if($showChart)
	@include('templates.default.reports.chart_truncation_notice')
	{{-- Chart: revenue vs cost vs profit (grouped bar, top invoices when many) --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-profit-summary"></div>
	</div>
	@endif

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>{{ $LANG['id'] ?? '' }}</th>
					<th>{{ $LANG['biller'] ?? '' }}</th>
					<th>{{ $LANG['customer'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['cost'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['profit'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach(($invoices ?? []) as $invoice)
				@php
					$profit = (float)($invoice['profit'] ?? 0);
					$profit_color = $profit >= 0 ? 'text-green' : 'text-red';
				@endphp
				@if($loop->index > 0 && (($invoices[$loop->index - 1]['preference'] ?? '') != ($invoice['preference'] ?? '')))
				<tr class="table-light">
					<td colspan="6" class="py-1"></td>
				</tr>
				@endif
				<tr>
					<td class="fw-medium">{{ $invoice['preference'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</td>
					<td>{{ $invoice['biller'] ?? '' }}</td>
					<td>{{ $invoice['customer'] ?? '' }}</td>
					<td class="text-end">{{ siLocal::number($invoice['invoice_total'] ?? 0) }}</td>
					<td class="text-end text-secondary">{{ siLocal::number($invoice['cost'] ?? 0) }}</td>
					<td class="text-end fw-bold {{ $profit_color }}">{{ siLocal::number($profit) }}</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold table-active">
					<td colspan="3" class="text-secondary">{{ strtoupper($LANG['totals'] ?? '') }}</td>
					<td class="text-end text-blue">{{ siLocal::number($sum_total) }}</td>
					<td class="text-end text-orange">{{ siLocal::number($sum_cost) }}</td>
					<td class="text-end {{ $sum_profit >= 0 ? 'text-green' : 'text-red' }}">{{ siLocal::number($sum_profit) }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@if($showChart)
<script>
(function () {
	var invoices = @json($chartInvoices);
	var labels   = invoices.map(function(r){ return (r.preference || '') + ' ' + (r.index_id || ''); });
	var totals   = invoices.map(function(r){ return parseFloat(r.invoice_total || 0); });
	var costs    = invoices.map(function(r){ return parseFloat(r.cost || 0); });
	var profits  = invoices.map(function(r){ return parseFloat(r.profit || 0); });

	var totalLbl  = @json($LANG['total']  ?? 'Total');
	var costLbl   = @json($LANG['cost']   ?? 'Cost');
	var profitLbl = @json($LANG['profit'] ?? 'Profit');
	var chartH    = Math.max(240, Math.min(400, labels.length * 28 + 80));

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var primary   = cssVar('--tblr-primary')       || '#45aaf2';
		var success   = cssVar('--tblr-success')       || '#2fb344';
		var danger    = cssVar('--tblr-danger')        || '#e03131';
		var bodyColor = cssVar('--tblr-body-color')    || (isDark ? '#c8d3e1' : '#1d273b');
		var borderCol = cssVar('--tblr-border-color')  || (isDark ? '#3d4555' : '#e6e7e9');

		// Colour each profit bar green or red based on sign
		var profitColours = profits.map(function(v){ return v >= 0 ? success : danger; });

		return {
			chart: {
				type: 'bar', fontFamily: 'inherit', height: chartH,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: [
				{ name: totalLbl,  data: totals  },
				{ name: costLbl,   data: costs   },
				{ name: profitLbl, data: profits }
			],
			xaxis: {
				categories: labels,
				labels: { style: { colors: bodyColor }, rotate: -35, hideOverlappingLabels: true },
				axisBorder: { color: borderCol }, axisTicks: { color: borderCol }
			},
			yaxis: { labels: { style: { colors: bodyColor }, formatter: function(v){ return v.toLocaleString(); } } },
			colors: [primary, '#f76707', success],
			plotOptions: { bar: { borderRadius: 3, columnWidth: '75%', grouped: true } },
			dataLabels: { enabled: false },
			legend: { position: 'bottom', labels: { colors: bodyColor } },
			grid: { borderColor: borderCol, strokeDashArray: 4 },
			tooltip: { theme: isDark ? 'dark' : 'light', y: { formatter: function(v){ return v.toLocaleString(); } } }
		};
	}

	function initChart() {
		var chart = new ApexCharts(document.getElementById('chart-profit-summary'), buildOptions());
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
@endif
