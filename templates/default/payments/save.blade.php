@if($saved === true || $saved === false)
@include('shared.save_alert', [
	'success' => ($saved === true),
	'title' => ($saved === true) ? 'Payment saved' : 'Payment not saved',
	'message' => outhtml($display_block ?? '')
])
@endif
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
