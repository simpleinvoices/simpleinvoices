{{-- /*
* Script: save.tpl
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

<div class="card">
	<div class="card-body">
		@if($saved == true)
			<div class="alert alert-success d-flex align-items-center" role="alert">
				<i class="ti ti-circle-check me-2 fs-3"></i>
				{!! outhtml($LANG['save_customer_success'] ?? '') !!}
			</div>
		@else
			<div class="alert alert-warning d-flex align-items-center" role="alert">
				<i class="ti ti-alert-circle me-2 fs-3"></i>
				{!! outhtml($LANG['save_customer_failure'] ?? '') !!}
			</div>
		@endif
	</div>
</div>
@if(post('cancel') == null)
	<meta http-equiv="refresh" content="2;URL=index.php?module=customers&amp;view=manage" />
@else
	<meta http-equiv="refresh" content="0;URL=index.php?module=customers&amp;view=manage" />
@endif
