{{-- /*
* Script: save.tpl
* 	Biller save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
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
			<div class="alert alert-success d-flex align-items-center mb-0" role="alert">
				<i class="ti ti-circle-check me-2" style="font-size: 1.5rem;"></i>
				<div>{!! outhtml($LANG['save_product_success'] ?? '') !!}</div>
			</div>
		@else
			<div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
				<i class="ti ti-circle-x me-2" style="font-size: 1.5rem;"></i>
				<div>{!! outhtml($LANG['save_product_failure'] ?? '') !!}</div>
			</div>
		@endif
	</div>
</div>
@if(post('cancel') == null)
	<meta http-equiv="refresh" content="2;URL=index.php?module=products&view=manage" />
@else
	<meta http-equiv="refresh" content="0;URL=index.php?module=products&view=manage" />
@endif
