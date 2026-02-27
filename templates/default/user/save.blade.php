{{-- /*
* Script: save.tpl
* 	User save template
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
	<div class="alert alert-success">{{ $LANG['save_user_success'] ?? '' }}</div>
@else
	<div class="alert alert-danger">{{ $LANG['save_user_failure'] ?? '' }}</div>
@endif


@if($smarty->post->cancel == null )
	<meta http-equiv="refresh" content="2;URL=index.php?module=user&view=manage" />
@else
	<meta http-equiv="refresh" content="0;URL=index.php?module=user&view=manage" />
@endif
