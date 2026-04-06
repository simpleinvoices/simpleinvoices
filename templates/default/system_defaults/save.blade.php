@include('shared.save_alert', [
	'success' => (bool) $saved,
	'title' => $saved ? 'Settings updated' : 'Settings not updated',
	'message' => outhtml($saved ? ($LANG['save_defaults_success'] ?? '') : ($LANG['save_defaults_failure'] ?? ''))
])
<meta http-equiv="refresh" content="2;URL=index.php?module=system_defaults&amp;view=manage" />
