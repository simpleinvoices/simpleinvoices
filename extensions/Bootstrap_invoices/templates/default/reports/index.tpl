<h1 class="title">{$LANG.all_reports}</h1>
<div class="si_index si_index_reports">
	<h2>{$LANG.statements}<a name="statement" href=""></a></h2>
	<div class="si_toolbar">
		<a href="index.php?module=statement&view=index" class="btn btn-default">
			<span class="glyphicon glyphicon-usd"></span>
			{$LANG.statement_of_invoices}
		</a>
	</div>
	
	<h2>{$LANG.sales}<a name="sales" href=""></a></h2>
	<div class="si_toolbar">
		<a href="index.php?module=reports&view=report_sales_total" class="btn btn-default">
			<span class="glyphicon glyphicon-usd"></span>
			{$LANG.total_sales}
		</a>
		<a href="index.php?module=reports&view=report_sales_by_periods" class="btn btn-default">
			<span class="glyphicon glyphicon-usd"></span>
			{$LANG.monthly_sales_per_year}
		</a>
		<a href="index.php?module=reports&view=report_sales_customers_total" class="btn btn-default">
			<span class="glyphicon glyphicon-usd"></span>
			{$LANG.sales_by_customers} 
		</a>					  
	</div>
	
	{if $defaults.inventory == "1"}
	<h2>{$LANG.profit}</h2>
	<div class="si_toolbar">
			<a href="index.php?module=reports&view=report_invoice_profit" class="btn btn-default">
				<span class="glyphicon glyphicon-usd"></span>
				{$LANG.profit_per_invoice}
			</a>
	</div>
	{/if}
	
	<h2>{$LANG.tax}</h2>
	<div class="si_toolbar">
		<a href="index.php?module=reports&view=report_tax_total" class="btn btn-default">
			<span class="glyphicon glyphicon-usd"></span>
			{$LANG.total_taxes}
		</a>
	</div>
	
	<h2>{$LANG.products}</h2>
	<div class="si_toolbar">
		<a href="index.php?module=reports&view=report_products_sold_total" class="btn btn-default">
			<span class="glyphicon glyphicon-shopping-cart"></span>
			{$LANG.product_sales}
		</a>
	
		<a href="index.php?module=reports&view=report_products_sold_by_customer" class="btn btn-default">
			<span class="glyphicon glyphicon-shopping-cart"></span>
			{$LANG.products_by_customer}
		</a>
	</div>
	
	
	<h2>{$LANG.biller_sales}</h2>
	<div class="si_toolbar">
		<a href="index.php?module=reports&view=report_biller_total" class="btn btn-default">
			<span class="glyphicon glyphicon-user"></span>
			{$LANG.biller_sales}
		</a>
	
		<a href="index.php?module=reports&view=report_biller_by_customer" class="btn btn-default">
			<span class="glyphicon glyphicon-user"></span>
			{$LANG.biller_sales_by_customer_totals} {* TODO change this - remove total *}
		</a>
	</div>
	
	<h2>{$LANG.debtors}</h2>
	<div class="si_toolbar">
		 <a href="index.php?module=reports&view=report_debtors_by_amount" class="btn btn-default">
			<span class="glyphicon glyphicon-user"></span>
			{$LANG.debtors_by_amount_owed}
		</a>
	
	   <a href="index.php?module=reports&view=report_debtors_by_aging" class="btn btn-default">
			<span class="glyphicon glyphicon-user"></span>
			 {$LANG.debtors_by_aging_periods}
		</a>
		
		 <a href="index.php?module=reports&view=report_debtors_owing_by_customer" class="btn btn-default">
			<span class="glyphicon glyphicon-user"></span>
			 {$LANG.total_owed_per_customer}
		</a>
	
	   <a href="index.php?module=reports&view=report_debtors_aging_total" class="btn btn-default">
			<span class="glyphicon glyphicon-user"></span>
			 {$LANG.total_by_aging_periods}
		</a>
	</div>
	
	<h2>{$LANG.other}</h2>
	<div class="si_toolbar">
		<a href="index.php?module=reports&view=database_log" class="btn btn-default">
			<span class="glyphicon glyphicon-open"></span>
			{$LANG.database_log}
		</a>
	</div>

</div>