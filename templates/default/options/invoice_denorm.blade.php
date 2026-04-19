<div class="card">
	<div class="card-body">
		<p class="text-secondary mb-4">{{ $LANG['invoice_denorm_intro'] ?? '' }}</p>

		@if(!empty($invoice_denorm_flash))
			<div class="alert alert-{{ $invoice_denorm_flash_type === 'success' ? 'success' : 'info' }} mb-4" role="alert">
				{{ $invoice_denorm_flash }}
			</div>
		@endif

		@if(!empty($invoice_denorm_verify))
			@php
				$checked = (int) ($invoice_denorm_verify['checked'] ?? 0);
				$bad = (int) ($invoice_denorm_verify['mismatches'] ?? 0);
			@endphp
			<div class="alert {{ $bad > 0 ? 'alert-warning' : 'alert-success' }} mb-4" role="alert">
				@if($bad === 0)
					{{ sprintf($LANG['invoice_denorm_verify_ok'] ?? '%d invoices checked; all match.', $checked) }}
				@else
					{{ sprintf($LANG['invoice_denorm_verify_bad'] ?? '%d of %d invoices have mismatched denormalised totals.', $bad, $checked) }}
				@endif
			</div>
		@endif

		<div class="row g-3">
			<div class="col-md-6">
				<form method="post" action="index.php?module=options&amp;view=invoice_denorm" class="card card-link card-link-pop border-0 shadow-none">
					<input type="hidden" name="csrfprotectionbysr" value="{{ $invoiceDenormCsrfToken ?? '' }}" />
					<input type="hidden" name="op" value="verify_denorm" />
					<button type="submit" class="card-body text-start w-100 btn btn-light border rounded-3">
						<div class="d-flex align-items-center">
							<i class="ti ti-search me-2" style="font-size: 2rem;"></i>
							<div>
								<div class="fw-medium">{{ $LANG['invoice_denorm_verify'] ?? 'Verify' }}</div>
								<div class="small text-secondary">{{ $LANG['invoice_denorm_verify_hint'] ?? '' }}</div>
							</div>
						</div>
					</button>
				</form>
			</div>
			<div class="col-md-6">
				<form method="post" action="index.php?module=options&amp;view=invoice_denorm" class="card card-link card-link-pop border-0 shadow-none"
					onsubmit="return confirm(@json($LANG['invoice_denorm_rebuild_confirm'] ?? 'Rebuild all denormalised invoice fields for this domain?'));">
					<input type="hidden" name="csrfprotectionbysr" value="{{ $invoiceDenormCsrfToken ?? '' }}" />
					<input type="hidden" name="op" value="rebuild_denorm" />
					<button type="submit" class="card-body text-start w-100 btn btn-light border rounded-3">
						<div class="d-flex align-items-center">
							<i class="ti ti-refresh me-2" style="font-size: 2rem;"></i>
							<div>
								<div class="fw-medium">{{ $LANG['invoice_denorm_rebuild'] ?? 'Rebuild' }}</div>
								<div class="small text-secondary">{{ $LANG['invoice_denorm_rebuild_hint'] ?? '' }}</div>
							</div>
						</div>
					</button>
				</form>
			</div>
		</div>

		<p class="small text-secondary mt-4 mb-0">{{ $LANG['invoice_denorm_footer'] ?? '' }}</p>
	</div>
</div>
