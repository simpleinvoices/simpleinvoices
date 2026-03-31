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
			<div class="alert alert-success" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($display_block ?? '') !!}
			</div>
		@elseif($saved === false)
			<div class="alert alert-warning" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($display_block ?? '') !!}
			</div>
		@endif
	</div>
</div>
{!! $refresh_total ?? '' !!}
