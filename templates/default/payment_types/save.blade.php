{{-- /*
* Script: save.tpl
* 	 Payment type save template
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
	'title' => ($saved == true) ? 'Payment type saved' : 'Payment type not saved',
	'message' => outhtml(($saved == true) ? ($LANG['save_payment_type_success'] ?? '') : ($LANG['save_payment_type_failure'] ?? ''))
])
@if($saved == true)
	<meta http-equiv="refresh" content="2;URL=index.php?module=payment_types&amp;view=manage" />
@endif
