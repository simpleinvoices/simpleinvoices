<div class="card">
	<div class="card-body">
		@if($saved == true)
			<div class="alert alert-success d-flex align-items-center" role="alert">
				<i class="ti ti-circle-check me-2 fs-3"></i>
				{!! outhtml($LANG['save_preference_success'] ?? '') !!}
			</div>
		@else
			<div class="alert alert-warning d-flex align-items-center" role="alert">
				<i class="ti ti-alert-circle me-2 fs-3"></i>
				{!! outhtml($LANG['save_preference_failure'] ?? '') !!}
			</div>
		@endif
	</div>
</div>
@if($saved == true)
	<meta http-equiv="refresh" content="2;URL=index.php?module=preferences&amp;view=manage" />
@endif
