{{-- /*
* View: save (Blade)
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

@php
	$invoiceFailMsg = (($save_error ?? '') === 'duplicate_product_description')
		? ($LANG['duplicate_product_description'] ?? '')
		: ($LANG['save_invoice_failure'] ?? '');
@endphp
@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? 'Invoice saved' : 'Invoice not saved',
	'message' => outhtml(($saved == true) ? ($LANG['save_invoice_success'] ?? '') : $invoiceFailMsg)
])
@if($saved == true && $id)
<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($id) }}" />
@endif
