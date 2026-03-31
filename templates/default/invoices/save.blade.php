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

<div class="card">
	<div class="card-body">
		@if($saved == true)
			<div class="alert alert-success d-flex align-items-center" role="alert">
				<i class="ti ti-circle-check me-2 fs-3"></i>
				{!! outhtml($LANG['save_invoice_success'] ?? '') !!}
			</div>
		@else
			<div class="alert alert-warning d-flex align-items-center" role="alert">
				<i class="ti ti-alert-circle me-2 fs-3"></i>
				{!! outhtml($LANG['save_invoice_failure'] ?? '') !!}
			</div>
		@endif
	</div>
</div>
<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($id) }}" />
