<div class="card">
	<div class="card-header">
		<span class="avatar avatar-sm bg-orange-lt me-2 rounded"><i class="ti ti-receipt-tax text-orange"></i></span>
		<h3 class="card-title">{{ $LANG['total_taxes'] ?? '' }}</h3>
		<div class="card-options">
			<a href="index.php?module=reports&view=index" class="btn btn-sm btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['reports'] ?? 'Reports' }}
			</a>
		</div>
	</div>
	<div class="card-body">
		<div class="row justify-content-center">
			<div class="col-sm-8 col-md-6 col-lg-4">
				<div class="card bg-orange-lt border-0">
					<div class="card-body text-center py-5">
						<div class="mb-3">
							<span class="avatar avatar-lg bg-orange text-white rounded-circle">
								<i class="ti ti-receipt-tax fs-2"></i>
							</span>
						</div>
						<div class="display-5 fw-bold text-orange mb-2">
							{{ siLocal::number($total_taxes ?? 0) ?: '-' }}
						</div>
						<div class="text-secondary">{{ $LANG['total_taxes'] ?? '' }}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
