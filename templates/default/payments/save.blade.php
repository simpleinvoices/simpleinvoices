<div class="card">
	<div class="card-body">
		@if(!empty($display_block))
			<div class="alert alert-info d-flex align-items-center mb-0" role="alert">
				<i class="ti ti-info-circle me-2" style="font-size: 1.5rem;"></i>
				<div>{{ $display_block }}</div>
			</div>
		@else
			<div class="alert alert-success d-flex align-items-center mb-0" role="alert">
				<i class="ti ti-circle-check me-2" style="font-size: 1.5rem;"></i>
				<div>{{ $LANG['payment_success'] ?? 'Payment processed.' }}</div>
			</div>
		@endif
		<p class="text-secondary mt-3 mb-0 small">{{ $LANG['redirecting'] ?? 'Redirecting...' }}</p>
	</div>
</div>
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
