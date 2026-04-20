@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? ($LANG['saved'] ?? 'Saved') : ($LANG['generic_error'] ?? 'Error'),
	'message' => outhtml(($saved == true) ? ($LANG['save_preference_success'] ?? '') : ($LANG['save_preference_failure'] ?? ''))
])
@if($saved == true && post('from_wizard') == '1')
	<meta http-equiv="refresh" content="1;URL=index.php?wizard_step=5" />
@elseif($saved == true)
	<meta http-equiv="refresh" content="2;URL=index.php?module=preferences&amp;view=manage" />
@else
	<meta http-equiv="refresh" content="2;URL=index.php?wizard_step=4" />
@endif
