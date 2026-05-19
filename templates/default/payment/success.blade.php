<div class="container-xl">
	<div class="page-header">
		<div class="row align-items-center">
			<div class="col-auto">
				<h2 class="page-title">{{ $LANG['payment_success_title'] ?? 'Payment Successful' }}</h2>
			</div>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<div class="card-body text-center py-5">
					<div class="mb-4">
						<span class="avatar avatar-xl bg-green-lt">
							<i class="ti ti-circle-check" style="font-size:2.5rem;color:#2fb344;"></i>
						</span>
					</div>
					<h3 class="mb-2">{{ $LANG['payment_success_title'] ?? 'Payment Successful' }}</h3>
					<p class="text-muted mb-4">{{ $LANG['payment_success_message'] ?? 'Your payment has been received. Thank you!' }}</p>
					<a href="./index.php?module=invoices&view=manage" class="btn btn-primary">
						<i class="ti ti-file-invoice me-2"></i>{{ $LANG['view_invoices'] ?? 'View Invoices' }}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
