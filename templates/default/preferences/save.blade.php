<div class="card">
	<div class="card-body">
		@if($saved == true)
			<div class="alert alert-success" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($LANG['save_preference_success'] ?? '') !!}
			</div>
		@else
			<div class="alert alert-warning" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($LANG['save_preference_failure'] ?? '') !!}
			</div>
		@endif
	</div>
</div>
@if($saved == true)
	<meta http-equiv="refresh" content="2;URL=index.php?module=preferences&amp;view=manage" />
@endif
