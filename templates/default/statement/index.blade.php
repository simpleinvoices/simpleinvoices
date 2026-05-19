{{-- Statement of Invoices --}}

@if($menu != false)
{{-- ── Filter card ─────────────────────────────────────────────────────────── --}}
<div class="card mb-4">
	<form name="frmpost" action="index.php?module=statement&amp;view=index" method="post">
		<div class="card-body">

			{{-- Biller + Customer --}}
			<div class="row g-3 mb-3">
				<div class="col-sm-6">
					<label class="form-label">{{ $LANG['biller'] ?? '' }}</label>
					@if($billers == null)
						<p class="text-secondary"><em>{{ $LANG['no_billers'] ?? '' }}</em></p>
					@else
						<select name="biller_id" class="form-select">
							@foreach(($billers ?? []) as $list_biller)
								<option value="{{ $list_biller['id'] ?? '' }}" @if($list_biller['id'] == $biller_id) selected @endif>
									{{ $list_biller['name'] ?? '' }}
								</option>
							@endforeach
						</select>
					@endif
				</div>
				<div class="col-sm-6">
					<label class="form-label">{{ $LANG['customer'] ?? '' }}</label>
					@if($customers == null)
						<p class="text-secondary"><em>{{ $LANG['no_customers'] ?? '' }}</em></p>
					@else
						<select name="customer_id" class="form-select">
							@foreach(($customers ?? []) as $list_customer)
								<option value="{{ $list_customer['id'] ?? '' }}" @if($list_customer['id'] == $customer_id) selected @endif>
									{{ $list_customer['name'] ?? '' }}
								</option>
							@endforeach
						</select>
					@endif
				</div>
			</div>

			{{-- Date range --}}
			<div class="row g-3 mb-3 align-items-end">
				<div class="col-sm-5">
					<label class="form-label">{{ $LANG['start_date'] ?? '' }}</label>
					<div class="input-icon">
						<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
						<input type="text" class="form-control date-picker" name="start_date" id="date1" value="{{ $start_date ?? '' }}" />
					</div>
				</div>
				<div class="col-sm-5">
					<label class="form-label">{{ $LANG['end_date'] ?? '' }}</label>
					<div class="input-icon">
						<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
						<input type="text" class="form-control date-picker" name="end_date" id="date2" value="{{ $end_date ?? '' }}" />
					</div>
				</div>
				<div class="col-sm-2">
					{{-- spacer so checkboxes below align nicely --}}
				</div>
			</div>

			{{-- Checkboxes --}}
			<div class="row g-3">
				<div class="col-sm-6">
					<label class="form-check">
						<input type="checkbox" class="form-check-input" name="filter_by_date" value="yes" @if($filter_by_date == "yes") checked @endif>
						<span class="form-check-label">{{ $LANG['filter_by_dates'] ?? '' }}</span>
					</label>
				</div>
				<div class="col-sm-6">
					<label class="form-check">
						<input type="checkbox" class="form-check-input" name="show_only_unpaid" value="yes" @if($show_only_unpaid == "yes") checked @endif>
						<span class="form-check-label">{{ $LANG['show_only_unpaid_invoices'] ?? '' }}</span>
					</label>
				</div>
			</div>
		</div>

		<div class="card-footer d-flex justify-content-end">
			<button type="submit" class="btn btn-primary" name="submit" value="statement_report">
				<i class="ti ti-chart-bar me-1"></i>{{ $LANG['run_report'] ?? '' }}
			</button>
		</div>
	</form>
</div>
@endif

{{-- ── Results ──────────────────────────────────────────────────────────────── --}}
@if(form_submitted() || $view == 'export')

@php
	$stmt_total  = $statement['total']  ?? 0;
	$stmt_paid   = $statement['paid']   ?? 0;
	$stmt_owing  = $statement['owing']  ?? 0;
	$invoice_count = count($invoices ?? []);
	$has_results = $invoice_count > 0;
@endphp

@if($menu == false)
{{-- Export / print view: minimal header --}}
<hr />
@endif

<div class="card {{ $menu != false ? '' : 'border-0 shadow-none' }}">

	@if($menu != false)
	{{-- ── Summary stats ──────────────────────────────────────────────────── --}}
	<div class="card-body border-bottom">
		<div class="row g-3 align-items-stretch">

			{{-- Left: biller + customer info --}}
			<div class="col-md-5">
				<div class="h-100 p-3 bg-blue-lt rounded-2">
					<div class="d-flex align-items-center gap-2 mb-2">
						<span class="avatar avatar-sm bg-blue text-white rounded"><i class="ti ti-building-store"></i></span>
						<div>
							<div class="text-secondary small">{{ $LANG['biller'] ?? '' }}</div>
							<div class="fw-semibold">{{ $biller_details['name'] ?? '-' }}</div>
						</div>
					</div>
					<div class="d-flex align-items-center gap-2">
						<span class="avatar avatar-sm bg-blue text-white rounded"><i class="ti ti-user"></i></span>
						<div>
							<div class="text-secondary small">{{ $LANG['customer'] ?? '' }}</div>
							<div class="fw-semibold">{{ $customer_details['name'] ?? '-' }}</div>
						</div>
					</div>
					@if($filter_by_date == "yes")
					<div class="mt-2 pt-2 border-top border-blue-subtle">
						<span class="badge bg-blue-lt text-blue">
							<i class="ti ti-calendar me-1"></i>{{ $start_date ?? '' }} - {{ $end_date ?? '' }}
						</span>
					</div>
					@endif
				</div>
			</div>

			{{-- Right: financial stats --}}
			<div class="col-md-7">
				<div class="row g-2 h-100">
					<div class="col-4">
						<div class="p-3 bg-secondary-lt rounded-2 text-center h-100 d-flex flex-column justify-content-center">
							<div class="text-secondary small mb-1">{{ $LANG['invoices'] ?? 'Invoices' }}</div>
							<div class="h3 fw-bold mb-0">{{ $invoice_count }}</div>
						</div>
					</div>
					<div class="col-4">
						<div class="p-3 bg-blue-lt rounded-2 text-center h-100 d-flex flex-column justify-content-center">
							<div class="text-secondary small mb-1">{{ $LANG['total'] ?? '' }}</div>
							<div class="h3 fw-bold text-blue mb-0">{{ siLocal::number($stmt_total) ?: '-' }}</div>
						</div>
					</div>
					<div class="col-4">
						<div class="p-3 bg-green-lt rounded-2 text-center h-100 d-flex flex-column justify-content-center">
							<div class="text-secondary small mb-1">{{ $LANG['paid'] ?? '' }}</div>
							<div class="h3 fw-bold text-green mb-0">{{ siLocal::number($stmt_paid) ?: '-' }}</div>
						</div>
					</div>
					<div class="col-12">
						<div class="p-3 {{ $stmt_owing > 0 ? 'bg-red-lt' : 'bg-green-lt' }} rounded-2 text-center">
							<div class="text-secondary small mb-1">{{ $LANG['owing'] ?? '' }}</div>
							<div class="h2 fw-bold {{ $stmt_owing > 0 ? 'text-red' : 'text-green' }} mb-0">
								{{ siLocal::number($stmt_owing) ?: '-' }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if($has_results && $stmt_total > 0)
	{{-- ── Chart: paid vs owing donut ──────────────────────────────────────── --}}
	<div class="card-body border-bottom p-2">
		<div id="chart-statement-summary"></div>
	</div>
	@endif

	{{-- ── Export actions ───────────────────────────────────────────────────── --}}
	<div class="card-body border-bottom py-2">
		<div class="d-flex flex-wrap gap-2">
			<a href="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=print"
			   class="btn btn-outline-primary btn-sm si-preview-link"
			   data-preview-title="{{ $LANG['print_preview'] ?? '' }}"
			   data-preview-pdf="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=pdf">
				<i class="ti ti-printer me-1"></i>{{ $LANG['print_preview'] ?? '' }}
			</a>
			<a href="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=pdf"
			   class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
				<i class="ti ti-file-type-pdf me-1"></i>{{ $LANG['export_pdf'] ?? '' }}
			</a>
			<a href="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=file&amp;filetype=xls"
			   class="btn btn-outline-secondary btn-sm">
				<i class="ti ti-file-spreadsheet me-1"></i>{{ $LANG['export_as'] ?? '' }} .xls
			</a>
			<a href="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=file&amp;filetype=doc"
			   class="btn btn-outline-secondary btn-sm">
				<i class="ti ti-file-text me-1"></i>{{ $LANG['export_as'] ?? '' }} .doc
			</a>
			<a href="index.php?module=statement&amp;view=email&amp;stage=1&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=file"
			   class="btn btn-outline-secondary btn-sm">
				<i class="ti ti-mail me-1"></i>{{ $LANG['email'] ?? '' }}
			</a>
		</div>
	</div>
	@endif {{-- $menu != false --}}

	{{-- ── Invoice table ────────────────────────────────────────────────────── --}}
	@if($has_results)
	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>{{ $LANG['id'] ?? '' }}</th>
					<th>{{ $LANG['date_upper'] ?? '' }}</th>
					<th>{{ $LANG['biller'] ?? '' }}</th>
					<th>{{ $LANG['customer'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['paid'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['owing'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach(($invoices ?? []) as $invoice)
				@if($loop->index > 0 && (($invoices[$loop->index - 1]['preference'] ?? '') != ($invoice['preference'] ?? '')))
				<tr><td colspan="7" class="py-1 table-light"></td></tr>
				@endif
				<tr>
					<td class="fw-medium">{{ $invoice['index_name'] ?? '' }}</td>
					<td class="text-secondary">{{ siLocal::date($invoice['date'] ?? '') }}</td>
					<td>{{ $invoice['biller'] ?? '' }}</td>
					<td>{{ $invoice['customer'] ?? '' }}</td>
					@if(($invoice['status'] ?? 0) > 0)
						<td class="text-end">{{ siLocal::number($invoice['invoice_total'] ?? 0) }}</td>
						<td class="text-end text-secondary">{{ siLocal::number($invoice['inv_paid'] ?? $invoice['INV_PAID'] ?? 0) }}</td>
						<td class="text-end fw-semibold {{ ($invoice['owing'] ?? 0) > 0 ? 'text-red' : 'text-secondary' }}">
							{{ siLocal::number($invoice['owing'] ?? 0) }}
						</td>
					@else
						<td class="text-end text-secondary"><em>{{ siLocal::number($invoice['invoice_total'] ?? 0) }}</em></td>
						<td class="text-end text-secondary" colspan="2">
							<span class="badge bg-secondary-lt text-secondary">{{ $LANG['void'] ?? 'Void' }}</span>
						</td>
					@endif
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold table-active">
					<td colspan="4" class="text-secondary">{{ $LANG['total'] ?? '' }}</td>
					<td class="text-end">{{ siLocal::number($stmt_total) }}</td>
					<td class="text-end text-green">{{ siLocal::number($stmt_paid) }}</td>
					<td class="text-end {{ $stmt_owing > 0 ? 'text-red' : 'text-secondary' }}">{{ siLocal::number($stmt_owing) }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
	@else
	<div class="card-body text-center text-secondary py-5">
		<i class="ti ti-file-off fs-1 d-block mb-2"></i>
		{{ $LANG['no_invoices'] ?? 'No invoices found.' }}
	</div>
	@endif

</div>{{-- /card --}}

@if($menu != false && $has_results && $stmt_total > 0)
<script>
(function () {
	var paid    = {{ (float)$stmt_paid }};
	var owing   = {{ (float)$stmt_owing }};
	var total   = {{ (float)$stmt_total }};
	var unpaid  = Math.max(0, total - paid - Math.max(0, owing));

	var paidLbl  = @json($LANG['paid']  ?? 'Paid');
	var owingLbl = @json($LANG['owing'] ?? 'Owing');
	var totalLbl = @json($LANG['total'] ?? 'Total');

	// Build series: paid portion + owing portion (may not sum to total if voided invoices exist)
	var series = [];
	var labels = [];
	if (paid  > 0) { series.push(paid);  labels.push(paidLbl);  }
	if (owing > 0) { series.push(owing); labels.push(owingLbl); }

	if (series.length === 0) return;

	function cssVar(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || ''; }

	function buildOptions() {
		var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		var success   = cssVar('--tblr-success') || '#2fb344';
		var danger    = cssVar('--tblr-danger')  || '#e03131';
		var bodyColor = cssVar('--tblr-body-color')   || (isDark ? '#c8d3e1' : '#1d273b');

		var colours = labels.map(function(l) {
			if (l === paidLbl)  return success;
			if (l === owingLbl) return danger;
			return '#45aaf2';
		});

		return {
			chart: {
				type: 'donut', fontFamily: 'inherit', height: 240,
				toolbar: { show: false }, animations: { enabled: false },
				background: 'transparent'
			},
			series: series,
			labels: labels,
			colors: colours,
			legend: { position: 'bottom', labels: { colors: bodyColor } },
			dataLabels: {
				enabled: true,
				formatter: function(val) { return val.toFixed(1) + '%'; },
				style: { fontSize: '12px' }
			},
			plotOptions: {
				pie: { donut: { size: '62%', labels: {
					show: true,
					total: {
						show: true, label: totalLbl, color: bodyColor,
						formatter: function() { return total.toLocaleString(); }
					},
					value: { color: bodyColor, formatter: function(v){ return parseFloat(v).toLocaleString(); } }
				}}}
			},
			tooltip: { theme: isDark ? 'dark' : 'light', y: { formatter: function(v){ return v.toLocaleString(); } } }
		};
	}

	function initChart() {
		var el = document.getElementById('chart-statement-summary');
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
		s.src = './templates/default/vendor/apexcharts/apexcharts.min.js';
		s.onload = initChart; document.head.appendChild(s);
	}
})();
</script>
@endif

@endif {{-- form_submitted || export --}}
