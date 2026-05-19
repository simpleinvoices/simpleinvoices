@if($saved === true || $saved === false)
@include('shared.save_alert', [
	'success' => ($saved === true),
	'title' => ($saved === true) ? ($LANG['payment_term_saved_title'] ?? 'Payment term saved') : ($LANG['payment_term_not_saved_title'] ?? 'Payment term not saved'),
	'message' => outhtml($display_block ?? '')
])
@endif
{!! $refresh_total ?? '' !!}
