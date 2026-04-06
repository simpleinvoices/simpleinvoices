{{-- /*
* Script: save.tpl
* 	Biller save template
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
	'title' => ($saved == true) ? 'Biller saved' : 'Biller not saved',
	'message' => outhtml(($saved == true) ? ($LANG['save_biller_success'] ?? '') : ($LANG['save_biller_failure'] ?? ''))
])
@if(post('from_wizard') == '1' && $saved)
	<meta http-equiv="refresh" content="1;URL=index.php?wizard_step=2" />
@elseif(post('cancel') == null)
	<meta http-equiv="refresh" content="2;URL=index.php?module=billers&amp;view=manage" />
@else
	<meta http-equiv="refresh" content="0;URL=index.php?module=billers&amp;view=manage" />
@endif
