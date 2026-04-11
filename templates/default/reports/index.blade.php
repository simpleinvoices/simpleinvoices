{{-- Reports Index --}}
<div class="row row-cards">

	{{-- Statements --}}
	<div class="col-md-6 col-xl-4">
		<div class="card h-100">
			<div class="card-header">
				<span class="avatar avatar-sm bg-blue-lt me-2 rounded"><i class="ti ti-file-invoice text-blue"></i></span>
				<h3 class="card-title">{{ $LANG['statements'] ?? '' }}</h3>
			</div>
			<div class="list-group list-group-flush">
				<a href="index.php?module=statement&view=index" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-file-description text-blue"></i>
						<span>{{ $LANG['statement_of_invoices'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
			</div>
		</div>
	</div>

	{{-- Sales --}}
	<div class="col-md-6 col-xl-4">
		<div class="card h-100">
			<div class="card-header">
				<span class="avatar avatar-sm bg-green-lt me-2 rounded"><i class="ti ti-trending-up text-green"></i></span>
				<h3 class="card-title">{{ $LANG['sales'] ?? '' }}</h3>
			</div>
			<div class="list-group list-group-flush">
				<a href="index.php?module=reports&view=report_sales_total" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-cash text-green"></i>
						<span>{{ $LANG['total_sales'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
				<a href="index.php?module=reports&view=report_sales_by_periods" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-calendar-stats text-green"></i>
						<span>{{ $LANG['monthly_sales_per_year'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
				<a href="index.php?module=reports&view=report_sales_customers_total" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-users text-green"></i>
						<span>{{ $LANG['sales_by_customers'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
			</div>
		</div>
	</div>

	{{-- Tax --}}
	<div class="col-md-6 col-xl-4">
		<div class="card h-100">
			<div class="card-header">
				<span class="avatar avatar-sm bg-orange-lt me-2 rounded"><i class="ti ti-receipt-tax text-orange"></i></span>
				<h3 class="card-title">{{ $LANG['tax'] ?? '' }}</h3>
			</div>
			<div class="list-group list-group-flush">
				<a href="index.php?module=reports&view=report_tax_total" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-receipt text-orange"></i>
						<span>{{ $LANG['total_taxes'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
			</div>
		</div>
	</div>

	{{-- Products --}}
	<div class="col-md-6 col-xl-4">
		<div class="card h-100">
			<div class="card-header">
				<span class="avatar avatar-sm bg-cyan-lt me-2 rounded"><i class="ti ti-package text-cyan"></i></span>
				<h3 class="card-title">{{ $LANG['products'] ?? '' }}</h3>
			</div>
			<div class="list-group list-group-flush">
				<a href="index.php?module=reports&view=report_products_sold_total" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-shopping-cart text-cyan"></i>
						<span>{{ $LANG['product_sales'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
				<a href="index.php?module=reports&view=report_products_sold_by_customer" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-users-group text-cyan"></i>
						<span>{{ $LANG['products_by_customer'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
			</div>
		</div>
	</div>

	{{-- Billers --}}
	<div class="col-md-6 col-xl-4">
		<div class="card h-100">
			<div class="card-header">
				<span class="avatar avatar-sm bg-indigo-lt me-2 rounded"><i class="ti ti-building text-indigo"></i></span>
				<h3 class="card-title">{{ $LANG['biller_sales'] ?? '' }}</h3>
			</div>
			<div class="list-group list-group-flush">
				<a href="index.php?module=reports&view=report_biller_total" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-building-store text-indigo"></i>
						<span>{{ $LANG['biller_sales'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
				<a href="index.php?module=reports&view=report_biller_by_customer" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-network text-indigo"></i>
						<span>{{ $LANG['biller_sales_by_customer_totals'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
			</div>
		</div>
	</div>

	{{-- Debtors --}}
	<div class="col-md-6 col-xl-4">
		<div class="card h-100">
			<div class="card-header">
				<span class="avatar avatar-sm bg-red-lt me-2 rounded"><i class="ti ti-alert-circle text-red"></i></span>
				<h3 class="card-title">{{ $LANG['debtors'] ?? '' }}</h3>
			</div>
			<div class="list-group list-group-flush">
				<a href="index.php?module=reports&view=report_debtors_by_amount" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-sort-descending-numbers text-red"></i>
						<span>{{ $LANG['debtors_by_amount_owed'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
				<a href="index.php?module=reports&view=report_debtors_by_aging" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-clock text-red"></i>
						<span>{{ $LANG['debtors_by_aging_periods'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
				<a href="index.php?module=reports&view=report_debtors_owing_by_customer" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-user-dollar text-red"></i>
						<span>{{ $LANG['total_owed_per_customer'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
				<a href="index.php?module=reports&view=report_debtors_aging_total" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-calendar-clock text-red"></i>
						<span>{{ $LANG['total_by_aging_periods'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
			</div>
		</div>
	</div>

	{{-- Profit (only if inventory enabled) --}}
	@if($defaults->inventory == "1")
	<div class="col-md-6 col-xl-4">
		<div class="card h-100">
			<div class="card-header">
				<span class="avatar avatar-sm bg-purple-lt me-2 rounded"><i class="ti ti-chart-line text-purple"></i></span>
				<h3 class="card-title">{{ $LANG['profit'] ?? '' }}</h3>
			</div>
			<div class="list-group list-group-flush">
				<a href="index.php?module=reports&view=report_invoice_profit" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-chart-dots text-purple"></i>
						<span>{{ $LANG['profit_per_invoice'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
			</div>
		</div>
	</div>
	@endif

	{{-- Other --}}
	<div class="col-md-6 col-xl-4">
		<div class="card h-100">
			<div class="card-header">
				<span class="avatar avatar-sm bg-secondary-lt me-2 rounded"><i class="ti ti-dots text-secondary"></i></span>
				<h3 class="card-title">{{ $LANG['other'] ?? '' }}</h3>
			</div>
			<div class="list-group list-group-flush">
				<a href="index.php?module=reports&view=database_log" class="list-group-item list-group-item-action">
					<div class="d-flex align-items-center gap-2">
						<i class="ti ti-database text-secondary"></i>
						<span>{{ $LANG['database_log'] ?? '' }}</span>
						<i class="ti ti-chevron-right ms-auto text-muted"></i>
					</div>
				</a>
			</div>
		</div>
	</div>

</div>
