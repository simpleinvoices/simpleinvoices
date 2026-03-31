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
		@if($saved === true)
			<div class="alert alert-success d-flex align-items-center" role="alert">
				<i class="ti ti-circle-check me-2 fs-3"></i>
				{!! outhtml($display_block ?? '') !!}
			</div>
		@elseif($saved === false)
			<div class="alert alert-warning d-flex align-items-center" role="alert">
				<i class="ti ti-alert-circle me-2 fs-3"></i>
				{!! outhtml($display_block ?? '') !!}
			</div>
		@endif
	</div>
</div>
{!! $refresh_total ?? '' !!}
