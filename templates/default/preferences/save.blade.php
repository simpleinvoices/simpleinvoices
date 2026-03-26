<div class="card">
	<div class="card-body">
		@if($saved == true)
			<div class="alert alert-success d-flex align-items-center mb-0" role="alert">
				<i class="ti ti-circle-check me-2" style="font-size: 1.5rem;"></i>
				<div>{{ $LANG['save_preference_success'] ?? '' }}</div>
			</div>
			<p class="text-secondary mt-3 mb-0 small">{{ $LANG['redirecting'] ?? 'Redirecting...' }}</p>
		@else
			<div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
				<i class="ti ti-circle-x me-2" style="font-size: 1.5rem;"></i>
				<div>{{ $LANG['save_preference_failure'] ?? '' }}</div>
			</div>
		@endif
	</div>
</div>
@if($saved == true)
	<meta http-equiv="refresh" content="2;URL=index.php?module=preferences&amp;view=manage" />
@endif
