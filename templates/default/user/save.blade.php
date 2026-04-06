{{-- /*
* Script: save.tpl
* 	User save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Soif
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/ --}}

@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? 'User saved' : 'User not saved',
	'message' => outhtml(($saved == true) ? ($LANG['save_user_success'] ?? '') : ($LANG['save_user_failure'] ?? ''))
])
@if(post('cancel') == null)
	<meta http-equiv="refresh" content="2;URL=index.php?module=user&view=manage" />
@else
	<meta http-equiv="refresh" content="0;URL=index.php?module=user&view=manage" />
@endif
