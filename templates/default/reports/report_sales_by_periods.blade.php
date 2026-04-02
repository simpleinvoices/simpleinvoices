@php
$show_rates = 1;
$rate_precision = 1;
$years = $all_years ?? [];
@endphp


@verbatim
<script>
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.but_show_rates').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			document.querySelectorAll('.rate').forEach(function(el) {
				el.style.display = el.style.display === 'none' ? '' : 'none';
			});
		});
	});
});
</script>
@endverbatim


<div class="card">
	<div class="card-body">
		<h4>{{ $LANG['sales'] ?? '' }}</h4>
@php $this_data = $data['sales'] ?? []; @endphp
		@include('templates.default.reports.report_sales_by_periods_include', ['this_data' => $this_data])

		<h4 class="mt-4">{{ $LANG['payments'] ?? '' }}</h4>
@php $this_data = $data['payments'] ?? []; @endphp
		@include('templates.default.reports.report_sales_by_periods_include', ['this_data' => $this_data])
	</div>
</div>
