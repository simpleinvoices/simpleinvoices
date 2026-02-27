<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['reports'] ?? 'Reports' }}</h3>
	</div>
	<div class="card-body">
		<h4 class="mb-3">{{ $LANG['statements'] ?? '' }}<a name="statement" href=""></a></h4>
		<ul class="list-group list-group-flush mb-4">
			<li class="list-group-item">
				<a href="index.php?module=statement&view=index" class="d-flex align-items-center text-reset">
					<i class="ti ti-report-money me-2"></i>
					{{ $LANG['statement_of_invoices'] ?? '' }}
				</a>
			</li>
		</ul>

		<h4 class="mb-3">{{ $LANG['sales'] ?? '' }}<a name="sales" href=""></a></h4>
		<ul class="list-group list-group-flush mb-4">
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_sales_total" class="d-flex align-items-center text-reset">
					<i class="ti ti-report-money me-2"></i>
					{{ $LANG['total_sales'] ?? '' }}
				</a>
			</li>
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_sales_by_periods" class="d-flex align-items-center text-reset">
					<i class="ti ti-report-money me-2"></i>
					{{ $LANG['monthly_sales_per_year'] ?? '' }}
				</a>
			</li>
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_sales_customers_total" class="d-flex align-items-center text-reset">
					<i class="ti ti-report-money me-2"></i>
					{{ $LANG['sales_by_customers'] ?? '' }}
				</a>
			</li>
		</ul>

		@if($defaults->inventory == "1")
		<h4 class="mb-3">{{ $LANG['profit'] ?? '' }}</h4>
		<ul class="list-group list-group-flush mb-4">
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_invoice_profit" class="d-flex align-items-center text-reset">
					<i class="ti ti-report-money me-2"></i>
					{{ $LANG['profit_per_invoice'] ?? '' }}
				</a>
			</li>
		</ul>
		@endif

		<h4 class="mb-3">{{ $LANG['tax'] ?? '' }}</h4>
		<ul class="list-group list-group-flush mb-4">
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_tax_total" class="d-flex align-items-center text-reset">
					<i class="ti ti-receipt-tax me-2"></i>
					{{ $LANG['total_taxes'] ?? '' }}
				</a>
			</li>
		</ul>

		<h4 class="mb-3">{{ $LANG['products'] ?? '' }}</h4>
		<ul class="list-group list-group-flush mb-4">
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_products_sold_total" class="d-flex align-items-center text-reset">
					<i class="ti ti-shopping-cart me-2"></i>
					{{ $LANG['product_sales'] ?? '' }}
				</a>
			</li>
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_products_sold_by_customer" class="d-flex align-items-center text-reset">
					<i class="ti ti-shopping-cart me-2"></i>
					{{ $LANG['products_by_customer'] ?? '' }}
				</a>
			</li>
		</ul>

		<h4 class="mb-3">{{ $LANG['biller_sales'] ?? '' }}</h4>
		<ul class="list-group list-group-flush mb-4">
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_biller_total" class="d-flex align-items-center text-reset">
					<i class="ti ti-user me-2"></i>
					{{ $LANG['biller_sales'] ?? '' }}
				</a>
			</li>
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_biller_by_customer" class="d-flex align-items-center text-reset">
					<i class="ti ti-user me-2"></i>
					{{ $LANG['biller_sales_by_customer_totals'] ?? '' }}
				</a>
			</li>
		</ul>

		<h4 class="mb-3">{{ $LANG['debtors'] ?? '' }}</h4>
		<ul class="list-group list-group-flush mb-4">
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_debtors_by_amount" class="d-flex align-items-center text-reset">
					<i class="ti ti-id me-2"></i>
					{{ $LANG['debtors_by_amount_owed'] ?? '' }}
				</a>
			</li>
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_debtors_by_aging" class="d-flex align-items-center text-reset">
					<i class="ti ti-id me-2"></i>
					{{ $LANG['debtors_by_aging_periods'] ?? '' }}
				</a>
			</li>
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_debtors_owing_by_customer" class="d-flex align-items-center text-reset">
					<i class="ti ti-id me-2"></i>
					{{ $LANG['total_owed_per_customer'] ?? '' }}
				</a>
			</li>
			<li class="list-group-item">
				<a href="index.php?module=reports&view=report_debtors_aging_total" class="d-flex align-items-center text-reset">
					<i class="ti ti-id me-2"></i>
					{{ $LANG['total_by_aging_periods'] ?? '' }}
				</a>
			</li>
		</ul>

		<h4 class="mb-3">{{ $LANG['other'] ?? '' }}</h4>
		<ul class="list-group list-group-flush">
			<li class="list-group-item">
				<a href="index.php?module=reports&view=database_log" class="d-flex align-items-center text-reset">
					<i class="ti ti-database me-2"></i>
					{{ $LANG['database_log'] ?? '' }}
				</a>
			</li>
		</ul>
	</div>
</div>
