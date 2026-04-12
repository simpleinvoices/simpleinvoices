@php
	$g = $report_chart_guard ?? [];
	$lim = (int) ($g['chart_limit'] ?? 10);
	$showNotice = !empty($g['chart_truncated']) || !empty($g['chart_threshold_blocked']);
@endphp
@if($showNotice)
<div class="mb-3">
	<div class="alert alert-info mb-0 d-flex align-items-start gap-2">
		<i class="ti ti-chart-dots fs-4 flex-shrink-0 mt-1"></i>
		<div>
			<div class="fw-semibold">{{ $LANG['report_chart_limited_title'] ?? '' }}</div>
			@if(!empty($g['chart_time_periods']) && !empty($g['chart_truncated']))
			<p class="mb-0 small text-secondary">
				{{ strtr($LANG['report_chart_truncated_years'] ?? '', [
					':shown' => (string) ($g['chart_shown_categories'] ?? $lim),
					':total' => (string) ($g['chart_total_categories'] ?? ''),
					':limit' => (string) $lim,
				]) }}
			</p>
			@elseif(!empty($g['chart_matrix']) && !empty($g['chart_truncated']))
			<p class="mb-0 small text-secondary">
				{{ strtr($LANG['report_chart_truncated_matrix'] ?? '', [
					':row_shown' => (string) ($g['chart_row_shown'] ?? $lim),
					':row_total' => (string) ($g['chart_row_total'] ?? ''),
					':series_shown' => (string) ($g['chart_series_shown'] ?? $lim),
					':series_total' => (string) ($g['chart_series_total'] ?? ''),
					':limit' => (string) $lim,
				]) }}
			</p>
			@elseif(!empty($g['chart_truncated']))
			<p class="mb-0 small text-secondary">
				{{ strtr($LANG['report_chart_truncated_summary'] ?? '', [
					':shown' => (string) ($g['chart_shown_categories'] ?? $lim),
					':total' => (string) ($g['chart_total_categories'] ?? ''),
					':limit' => (string) $lim,
				]) }}
			</p>
			@elseif(!empty($g['chart_threshold_blocked']))
			<p class="mb-0 small text-secondary">{{ $LANG['report_chart_threshold_only'] ?? '' }}</p>
			@endif
			@if(!empty($g['chart_threshold_blocked']))
			<p class="small text-secondary mb-0 mt-2">{{ $LANG['report_chart_threshold_detail'] ?? '' }}</p>
			<ul class="small text-secondary mb-0 mt-1">
				@if(!empty($g['skip_inv']))
				<li>{{ $LANG['report_chart_skip_invoices'] ?? '' }}: {{ $g['inv'] ?? '—' }} &gt; {{ $g['max_inv'] ?? 1000 }}</li>
				@endif
				@if(!empty($g['skip_cat']))
				<li>{{ $LANG['report_chart_skip_categories'] ?? '' }}: {{ $g['cat_count'] ?? '—' }} &gt; {{ $g['max_cat'] ?? 150 }}</li>
				@endif
				@if(!empty($g['skip_ser']))
				<li>{{ $LANG['report_chart_skip_series'] ?? '' }}: {{ $g['ser_count'] ?? '—' }} &gt; {{ $g['max_ser'] ?? 150 }}</li>
				@endif
				@if(!empty($g['skip_cells']))
				<li>{{ $LANG['report_chart_skip_density'] ?? '' }}: {{ $g['cell_count'] ?? '—' }} &gt; {{ $g['max_cells'] ?? 2500 }}</li>
				@endif
			</ul>
			@endif
		</div>
	</div>
</div>
@endif

