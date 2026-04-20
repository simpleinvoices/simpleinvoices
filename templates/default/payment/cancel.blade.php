<div class="container-xl">
	<div class="page-header">
		<div class="row align-items-center">
			<div class="col-auto">
				<h2 class="page-title">{{ $LANG['payment_cancelled_title'] ?? 'Payment Cancelled' }}</h2>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<div class="card-body text-center py-5">
					<div class="mb-4">
						<span class="avatar avatar-xl bg-yellow-lt">
							<i class="ti ti-alert-triangle" style="font-size:2.5rem;color:#f76707;"></i>
						</span>
					</div>
					<h3 class="mb-2">{{ $LANG['payment_cancelled_title'] ?? 'Payment Cancelled' }}</h3>
					<p class="text-muted mb-4">{{ $LANG['payment_cancelled_message'] ?? 'Your payment was not completed. You can try again from your invoice.' }}</p>
					<a href="./index.php?module=invoices&view=manage" class="btn btn-secondary">
						<i class="ti ti-arrow-left me-2"></i>{{ $LANG['back_to_invoices'] ?? 'Back to Invoices' }}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
