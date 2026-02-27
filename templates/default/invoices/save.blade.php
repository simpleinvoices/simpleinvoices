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

@if($saved == true )
	<div class="alert alert-success">{{ $LANG['save_invoice_success'] ?? '' }}</div>
@else
	<div class="alert alert-danger">{{ $LANG['save_invoice_failure'] ?? '' }}</div>
@endif


<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($id) }}" />
