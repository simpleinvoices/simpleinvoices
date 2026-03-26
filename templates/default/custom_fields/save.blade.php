{{-- /*
* Script: save.tpl
* 	Custom fields save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/ --}}

<div class="card">
	<div class="card-body">
		<div class="alert alert-info d-flex align-items-center mb-0" role="alert">
			<i class="ti ti-info-circle me-2" style="font-size: 1.5rem;"></i>
			<div>{{ $display_block }}</div>
		</div>
		@if(!empty($refresh_total))
			<p class="text-secondary mt-3 mb-0 small">{{ $LANG['redirecting'] ?? 'Redirecting...' }}</p>
		@endif
	</div>
</div>
{!! $refresh_total ?? '' !!}
