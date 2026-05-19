@php
	$g = $report_chart_guard ?? [];
@endphp
@if(!empty($g['chart_omitted_invoice_cap']))
<div class="card-body border-bottom py-2">
	<div class="alert alert-warning mb-0 d-flex align-items-start gap-2">
		<i class="ti ti-chart-bar-off fs-4 flex-shrink-0 mt-1"></i>
		<div>
			<div class="fw-semibold">{{ $LANG['report_chart_omitted_invoice_title'] ?? '' }}</div>
			<p class="mb-0 small text-secondary">
				{{ strtr($LANG['report_chart_omitted_invoice_body'] ?? '', [
					':count' => (string) ($g['chart_omitted_inv'] ?? ''),
					':max' => (string) ($g['chart_omitted_max'] ?? 1000),
				]) }}
			</p>
		</div>
	</div>
</div>
@endif
