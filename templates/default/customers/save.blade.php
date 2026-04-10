{{-- /*
* View: save (Blade)
* 	 Customer save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Soif
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? 'Customer saved' : 'Customer not saved',
	'message' => outhtml(($saved == true) ? ($LANG['save_customer_success'] ?? '') : ($LANG['save_customer_failure'] ?? ''))
])
@if(post('from_wizard') == '1' && $saved)
	<meta http-equiv="refresh" content="1;URL=index.php?wizard_step=3" />
@elseif(post('cancel') == null)
	<meta http-equiv="refresh" content="2;URL=index.php?module=customers&amp;view=manage" />
@else
	<meta http-equiv="refresh" content="0;URL=index.php?module=customers&amp;view=manage" />
@endif
