@php
$show_rates = 1;
$rate_precision = 1;
$years = $all_years ?? [];
$chart_js_years = $chart_years ?? $years;
$rg = $report_chart_guard ?? ['enabled' => true];
$currencies_data = $currencies_data ?? [];
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

@if(empty($currencies_data))
<div class="card">
	<div class="card-body text-center text-secondary py-5">
		<i class="ti ti-calendar-off fs-1 d-block mb-2"></i>
		<div>{{ $LANG['no_data'] ?? 'No data available.' }}</div>
	</div>
</div>
@endif

@foreach($currencies_data as $curr_key => $data)
@php
    $currency_sign = $data['currency_sign'] ?? $curr_key;
    $currency_code = $data['currency_code'] ?? '';
    $decodedSign = \CurrencySignHelper::forDisplay($currency_sign);
@endphp
<div class="card @if(!$loop->last) mb-3 @endif">
	<div class="card-header">
		<h4 class="card-title mb-0 d-flex align-items-center gap-2">
			<span class="badge bg-blue-lt text-blue fs-6">{{ $decodedSign ?: $currency_sign }}{{ $currency_code ? ' ' . $currency_code : '' }}</span>
		</h4>
		<div class="card-options">
			<button type="button" class="btn btn-sm btn-outline-secondary but_show_rates" title="{{ $LANG['show_rates'] ?? 'Toggle growth rates' }}">
				<i class="ti ti-percentage me-1"></i>{{ $LANG['show_rates'] ?? '%' }}
			</button>
		</div>
	</div>
	<div class="card-body">
		@if(!empty($rg['chart_truncated']) || !empty($rg['chart_threshold_blocked']))
		@include('templates.default.reports.chart_truncation_notice')
		@endif
		<div class="row g-4">
			<div class="col-md-6">
				<h5 class="mb-3 d-flex align-items-center gap-2">
					<span class="avatar avatar-sm bg-green-lt rounded"><i class="ti ti-cash text-green"></i></span>
					{{ $LANG['sales'] ?? '' }}
				</h5>
				@php $this_data = $data['sales'] ?? []; @endphp
				@include('templates.default.reports.report_sales_by_periods_include', [
					'this_data' => $this_data,
					'chartId'   => 'chart-periods-sales-' . md5($curr_key),
					'chartColor' => 'success',
					'report_chart_guard' => $rg,
					'years' => $years,
					'chart_js_years' => $chart_js_years,
					'summary_currency_sign' => $currency_sign,
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
					'chartId'   => 'chart-periods-payments-' . md5($curr_key),
					'chartColor' => 'primary',
					'report_chart_guard' => $rg,
					'years' => $years,
					'chart_js_years' => $chart_js_years,
					'summary_currency_sign' => $currency_sign,
				])
			</div>
		</div>
	</div>
</div>
@endforeach
