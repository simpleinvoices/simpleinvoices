{{-- /*
* View: save (Blade)
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

@if($saved === true || $saved === false)
@include('shared.save_alert', [
	'success' => ($saved === true),
	'title' => ($saved === true) ? 'Custom field saved' : 'Custom field not saved',
	'message' => outhtml($display_block ?? '')
])
@endif
{!! $refresh_total ?? '' !!}
