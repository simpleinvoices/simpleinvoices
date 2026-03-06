{{-- Display the rate column ? --}}
{assign var=show_rates value=1}

{{-- How may decimals for rate (0-2) --}}
{assign var=rate_precision value='1'}


{{-- ------------------------------------------------------------------------------- --}}

{assign var=years_shown value=$all_years|@count}
{assign var=years_shown value=$years_shown-1}
{assign var=years value=$all_years['0']|range:$all_years.$years_shown}


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
		@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.report_sales_by_periods_include', ['this_data' => $this_data])

		<h4 class="mt-4">{{ $LANG['payments'] ?? '' }}</h4>
@php $this_data = $data['payments'] ?? []; @endphp
		@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.report_sales_by_periods_include', ['this_data' => $this_data])
	</div>
</div>
