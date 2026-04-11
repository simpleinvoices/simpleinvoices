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
	<div class="card-header">
		<span class="avatar avatar-sm bg-green-lt me-2 rounded"><i class="ti ti-calendar-stats text-green"></i></span>
		<h3 class="card-title">{{ $LANG['monthly_sales_per_year'] ?? '' }}</h3>
		<div class="card-options">
			<button class="btn btn-sm btn-outline-secondary but_show_rates me-2" title="{{ $LANG['show_rates'] ?? 'Toggle growth rates' }}">
				<i class="ti ti-percentage me-1"></i>{{ $LANG['show_rates'] ?? '%' }}
			</button>
			<a href="index.php?module=reports&view=index" class="btn btn-sm btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['reports'] ?? 'Reports' }}
			</a>
		</div>
	</div>

	<div class="card-body">
		<div class="row g-4">
			<div class="col-md-6">
				<h5 class="mb-3 d-flex align-items-center gap-2">
					<span class="avatar avatar-sm bg-green-lt rounded"><i class="ti ti-cash text-green"></i></span>
					{{ $LANG['sales'] ?? '' }}
				</h5>
				@php $this_data = $data['sales'] ?? []; @endphp
				@include('templates.default.reports.report_sales_by_periods_include', ['this_data' => $this_data])
			</div>
			<div class="col-md-6">
				<h5 class="mb-3 d-flex align-items-center gap-2">
					<span class="avatar avatar-sm bg-blue-lt rounded"><i class="ti ti-credit-card text-blue"></i></span>
					{{ $LANG['payments'] ?? '' }}
				</h5>
				@php $this_data = $data['payments'] ?? []; @endphp
				@include('templates.default.reports.report_sales_by_periods_include', ['this_data' => $this_data])
			</div>
		</div>
	</div>
</div>
