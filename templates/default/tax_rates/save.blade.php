@if($saved === true || $saved === false)
@include('shared.save_alert', [
	'success' => ($saved === true),
	'title' => ($saved === true) ? 'Tax rate saved' : 'Tax rate not saved',
	'message' => outhtml($display_block ?? '')
])
@endif
{!! $refresh_total ?? '' !!}
