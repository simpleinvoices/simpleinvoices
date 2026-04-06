@if($saved === true || $saved === false)
@include('shared.save_alert', [
	'success' => ($saved === true),
	'title' => ($saved === true) ? 'Product attribute saved' : 'Product attribute not saved',
	'message' => outhtml($display_block ?? '')
])
@endif
{!! $refresh_total ?? '' !!}
