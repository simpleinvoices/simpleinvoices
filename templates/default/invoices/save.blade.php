{{-- /*
* Script: save.tpl
* 	Invoice save template
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
	'title' => ($saved == true) ? 'Invoice saved' : 'Invoice not saved',
	'message' => outhtml(($saved == true) ? ($LANG['save_invoice_success'] ?? '') : ($LANG['save_invoice_failure'] ?? ''))
])
<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($id) }}" />
