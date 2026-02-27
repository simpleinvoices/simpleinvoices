


@if($saved == true )
	<div class="alert alert-success">{{ $LANG['save_preference_success'] ?? '' }}</div>
@else
	<div class="alert alert-danger">{{ $LANG['save_preference_failure'] ?? '' }}</div>
@endif


@if($saved == true )
	<meta http-equiv="refresh" content="2;URL=index.php?module=preferences&amp;view=manage" />
@endif
