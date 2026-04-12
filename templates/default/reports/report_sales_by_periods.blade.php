@php
$show_rates = 1;
$rate_precision = 1;
$years = $all_years ?? [];
$chart_js_years = $chart_years ?? $years;
$rg = $report_chart_guard ?? ['enabled' => true];
@endphp

@verbatim
<script>
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.but_show_rates').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			var isVisible = document.querySelector('.rate') && document.querySelector('.rate').style.display !== 'none';
			document.querySelectorAll('.rate').forEach(function(el) {
				el.style.display = isVisible ? 'none' : '';
			});
			this.classList.toggle('btn-outline-secondary', isVisible);
			this.classList.toggle('btn-secondary', !isVisible);
		});
	});
});
</script>
@endverbatim

<div class="card">
	<div class="card-body">
		@if(!empty($rg['chart_truncated']) || !empty($rg['chart_threshold_blocked']))
		@include('templates.default.reports.chart_truncation_notice')
		@endif
		<div class="d-flex justify-content-end mb-3">
			<button type="button" class="btn btn-sm btn-outline-secondary but_show_rates" title="{{ $LANG['show_rates'] ?? 'Toggle growth rates' }}">
				<i class="ti ti-percentage me-1"></i>{{ $LANG['show_rates'] ?? '%' }}
			</button>
		</div>
		<div class="row g-4">
			<div class="col-md-6">
				<h5 class="mb-3 d-flex align-items-center gap-2">
					<span class="avatar avatar-sm bg-green-lt rounded"><i class="ti ti-cash text-green"></i></span>
					{{ $LANG['sales'] ?? '' }}
				</h5>
				@php $this_data = $data['sales'] ?? []; @endphp
				@include('templates.default.reports.report_sales_by_periods_include', [
					'this_data' => $this_data,
					'chartId'   => 'chart-periods-sales',
					'chartColor' => 'success',
					'report_chart_guard' => $rg,
					'years' => $years,
					'chart_js_years' => $chart_js_years,
				])
			</div>
			<div class="col-md-6">
				<h5 class="mb-3 d-flex align-items-center gap-2">
					<span class="avatar avatar-sm bg-blue-lt rounded"><i class="ti ti-credit-card text-blue"></i></span>
					{{ $LANG['payments'] ?? '' }}
				</h5>
				@php $this_data = $data['payments'] ?? []; @endphp
				@include('templates.default.reports.report_sales_by_periods_include', [
					'this_data' => $this_data,
					'chartId'   => 'chart-periods-payments',
					'chartColor' => 'primary',
					'report_chart_guard' => $rg,
					'years' => $years,
					'chart_js_years' => $chart_js_years,
				])
			</div>
		</div>
	</div>
</div>
