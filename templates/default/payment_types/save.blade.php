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

<div class="card">
	<div class="card-body">
		@if($saved == true)
			<div class="alert alert-success" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($LANG['save_payment_type_success'] ?? '') !!}
			</div>
		@else
			<div class="alert alert-warning" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($LANG['save_payment_type_failure'] ?? '') !!}
			</div>
		@endif
	</div>
</div>
@if($saved == true)
	<meta http-equiv="refresh" content="2;URL=index.php?module=payment_types&amp;view=manage" />
@endif
