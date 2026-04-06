@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? 'Preference saved' : 'Preference not saved',
	'message' => outhtml(($saved == true) ? ($LANG['save_preference_success'] ?? '') : ($LANG['save_preference_failure'] ?? ''))
])
@if($saved == true)
	<meta http-equiv="refresh" content="2;URL=index.php?module=preferences&amp;view=manage" />
@endif
