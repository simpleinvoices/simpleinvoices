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
		<div class="row g-4">
			<div class="col-md-6">
				<h4>{{ $LANG['sales'] ?? '' }}</h4>
@php $this_data = $data['sales'] ?? []; @endphp
				@include('templates.default.reports.report_sales_by_periods_include', ['this_data' => $this_data])
			</div>
			<div class="col-md-6">
				<h4>{{ $LANG['payments'] ?? '' }}</h4>
@php $this_data = $data['payments'] ?? []; @endphp
				@include('templates.default.reports.report_sales_by_periods_include', ['this_data' => $this_data])
			</div>
		</div>
	</div>
</div>
