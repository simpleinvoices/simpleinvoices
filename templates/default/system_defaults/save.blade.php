
@if($saved )
	<div class="alert alert-success">{{ $LANG['save_defaults_success'] ?? '' }}</div>
@else
	<div class="alert alert-danger">{{ $LANG['save_defaults_failure'] ?? '' }}</div>
@endif


<meta http-equiv="refresh" content="2;URL=index.php?module=system_defaults&amp;view=manage" />
