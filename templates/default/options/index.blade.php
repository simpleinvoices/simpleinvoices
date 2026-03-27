<div class="card">
	<div class="card-body">
		<div class="row row-cards mb-4">
			<div class="col-md-4">
				<a href="index.php?module=system_defaults&amp;view=manage" class="card card-link card-link-pop">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="me-2 ti ti-settings" style="font-size: 2rem;"></span>
							<div>{{ $LANG['system_preferences'] ?? '' }}</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4">
				<a href="index.php?module=custom_fields&amp;view=manage" class="card card-link card-link-pop">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<i class="ti ti-forms me-2" style="font-size: 2rem;"></i>
							<div>{{ $LANG['custom_fields_upper'] ?? '' }}</div>
						</div>
					</div>
				</a>
			</div>
		</div>

		<h4 class="mb-3">{{ $LANG['invoice_settings'] ?? 'Invoice settings' }}</h4>
		<div class="row row-cards mb-4">
			<div class="col-md-4">
				<a href="index.php?module=tax_rates&amp;view=manage" class="card card-link card-link-pop">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<i class="ti ti-receipt-tax me-2" style="font-size: 2rem;"></i>
							<div>{{ $LANG['tax_rates'] ?? '' }}</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4">
				<a href="index.php?module=preferences&amp;view=manage" class="card card-link card-link-pop">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<i class="ti ti-file-text me-2" style="font-size: 2rem;"></i>
							<div>{{ $LANG['invoice_preferences'] ?? '' }}</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4">
				<a href="index.php?module=payment_types&amp;view=manage" class="card card-link card-link-pop">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<i class="ti ti-credit-card me-2" style="font-size: 2rem;"></i>
							<div>{{ $LANG['payment_types'] ?? '' }}</div>
						</div>
					</div>
				</a>
			</div>
		</div>

		<h4 class="mb-3">{{ $LANG['database_tools'] ?? 'Database tools' }}</h4>
		<div class="row row-cards">
			<div class="col-md-4">
				<a href="index.php?module=options&amp;view=backup_database" class="card card-link card-link-pop">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<i class="ti ti-database-export me-2" style="font-size: 2rem;"></i>
							<div>{{ $LANG['backup_database'] ?? '' }}</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4">
				<a href="index.php?module=options&amp;view=manage_sqlpatches" class="card card-link card-link-pop">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<i class="ti ti-database me-2" style="font-size: 2rem;"></i>
							<div>{{ $LANG['database_upgrade_manager'] ?? '' }}</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4">
				<a href="index.php?module=options&amp;view=manage_cronlog" class="card card-link card-link-pop">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<i class="ti ti-file-description me-2" style="font-size: 2rem;"></i>
							<div>{{ $LANG['cron_log'] ?? 'Cron log' }}</div>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>
