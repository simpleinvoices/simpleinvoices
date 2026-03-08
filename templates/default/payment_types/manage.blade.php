{{-- /*
* Script: manage.tpl
* 	 Manage payment types template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card">
@if($paymentTypes==null )
	<div class="alert alert-info mb-0">{{ $LANG['no_payment_types'] ?? '' }}</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.payment_types.manage_js')
@endif
</div>
