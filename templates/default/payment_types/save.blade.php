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

@if($saved == true )
	<div class="alert alert-success">{{ $LANG['save_payment_type_success'] ?? '' }}</div>
@else
	<div class="alert alert-danger">{{ $LANG['save_payment_type_failure'] ?? '' }}</div>
@endif


@if($saved == true )
	<meta http-equiv="refresh" content="2;URL=index.php?module=payment_types&amp;view=manage" />
@endif
