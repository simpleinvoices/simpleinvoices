<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			{$smarty.capture.hook_tabmenu_start}
			<a class="navbar-brand" href="#"> {$smarty.session.Zend_Auth.email|htmlsafe} </a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
			{$smarty.capture.hook_tabmenu_main_start}
			
			<!--BEGIN MENU -->
			
				<!--HOME -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$LANG.home} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="index.php?module=index&amp;view=index">{$LANG.dashboard}</a></li>
						<li><a href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a></li>
					</ul>
				</li>
				
				<!--MONEY -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$LANG.money} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="index.php?module=invoices&amp;view=manage">{$LANG.invoices}</a></li>
						<li><a href="index.php?module=invoices&amp;view=itemised">{$LANG.new_invoice}</a></li>
						<li><a href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a></li>
						<li><a href="index.php?module=payments&amp;view=manage">{$LANG.payments}</a></li>
						<li><a href="index.php?module=reports&amp;view=report_sales_total">{$LANG.sales_report}</a></li>
					</ul>
				</li>
				
				<!--PEOPLE -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$LANG.people} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a></li>
						<li><a href="index.php?module=billers&amp;view=manage">{$LANG.billers}</a></li>
						<li><a href="index.php?module=user&amp;view=manage">{$LANG.users}</a></li>
					</ul>
				</li>
				
				<!--PRODUCTS -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$LANG.products} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="index.php?module=products&amp;view=manage">{$LANG.manage_products}</a></li>
						<li><a href="index.php?module=products&amp;view=add">{$LANG.add_product}</a></li>
					</ul>
				</li>
				
				{$smarty.capture.hook_tabmenu_main_end}
				
				<!--SETTINGS -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$LANG.settings} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="index.php?module=options&amp;view=index">{$LANG.settings}</a></li>
						<li><a href="index.php?module=system_defaults&amp;view=manage">{$LANG.system_preferences}</a></li>
						<li><a href="index.php?module=custom_fields&amp;view=manage">{$LANG.custom_fields_upper}</a></li>
						<li><a href="index.php?module=tax_rates&amp;view=manage">{$LANG.tax_rates}</a></li>
						<li><a href="index.php?module=preferences&amp;view=manage">{$LANG.invoice_preferences}</a></li>
						<li><a href="index.php?module=payment_types&amp;view=manage">{$LANG.payment_types}</a></li>
						<li><a href="index.php?module=options&amp;view=backup_database">{$LANG.backup_database}</a></li>
					</ul>
				</li>
				
				{$smarty.capture.hook_tabmenu_end}
			<!--END MENU -->
			</ul>
		</div>
	</div>
</div>
<div class="col si_wrap" id="page_title">
	<h1>
		{ if $pageActive == "dashboard"}{$LANG.dashboard}{/if}
		{ if $pageActive == "report"}{$LANG.all_reports}{/if}
		{ if $pageActive == "invoice"}{$LANG.invoices}{/if}
		{ if $subPageActive == "invoice_edit"}{$LANG.edit}{/if}
		{ if $subPageActive == "invoice_view"}{$LANG.quick_view}{/if}
		{ if $pageActive == "invoice_new"}{$LANG.new_invoice}{/if}
		{ if $subPageActive == "invoice_new_itemised"}{$LANG.itemised}{/if}
		{ if $subPageActive == "invoice_new_total"}{$LANG.total}{/if}
		
		{ if $pageActive == "cron"}{$LANG.recurrence}{/if}
		{ if $subPageActive == "cron_add"}{$LANG.add}{/if}
		{ if $subPageActive == "cron_edit"}{$LANG.edit}{/if}
		{ if $subPageActive == "cron_view"}{$LANG.view}{/if}
		{ if $pageActive == "payment"}{$LANG.payments}{/if}
		{ if $subPageActive == "payment_process"}{$LANG.process}{/if}
		{ if $subPageActive == "payment_eway"}{$LANG.eway}{/if}
		{ if $subPageActive == "payment_filter_invoice"}{$LANG.payments_filtered}{$preference.pref_inv_wording|htmlsafe}{$smarty.get.id|htmlsafe}{/if}
		{ if $subPageActive == "payment_filter_customer"}{$LANG.payments_filtered_customer} '{$customer.name}'{/if}
		{ if $pageActive == "report_sale"}{$LANG.sales_report}{/if}
		{ if $pageActive == "customer"}{$LANG.customers}{/if}
		{ if $subPageActive == "customer_add"}{$LANG.add}{/if}
		{ if $subPageActive == "customer_view"}{$LANG.view}{/if}
		{ if $subPageActive == "customer_edit"}{$LANG.edit}{/if}
		{ if $pageActive == "biller"}{$LANG.billers}{/if}
		{ if $subPageActive == "biller_add"}{$LANG.add}{/if}
		{ if $subPageActive == "biller_view"}{$LANG.view}{/if}
		{ if $subPageActive == "biller_edit"}{$LANG.edit}{/if}
		{ if $pageActive == "user"}{$LANG.users}{/if}
		{ if $subPageActive == "user_add"}{$LANG.add}{/if}
		{ if $subPageActive == "user_view"}{$LANG.view}{/if}
		{ if $subPageActive == "user_edit"}{$LANG.edit}{/if}
		{ if $pageActive == "product_manage"} {$LANG.manage_products}{/if}
		{ if $subPageActive == "product_view"}{$LANG.view}{/if}
		{ if $subPageActive == "product_edit"}{$LANG.edit}{/if}
		{ if $pageActive == "product_add"}{$LANG.add_product} {/if}
		{if $defaults.inventory == "1"}
		{ if $pageActive == "inventory"}{$LANG.inventory}{/if} 
		{ if $subPageActive == "inventory_view"}{$LANG.view}{/if}
		{ if $subPageActive == "inventory_edit"}{$LANG.edit}{/if}
		{ if $subPageActive == "inventory_add"}{$LANG.add}{/if}
		{/if}
		{if $defaults.product_attributes}
		{ if $pageActive == "inventory"} {$LANG.product_attributes}{/if}
		{ if $subPageActive == "inventory_view"}{$LANG.view}{/if}
		{ if $subPageActive == "inventory_edit"}{$LANG.edit}{/if}
		{ if $subPageActive == "inventory_add"}{$LANG.add}{/if}
		{ if $pageActive == "inventory"}{$LANG.product_values}{/if}
		{ if $subPageActive == "inventory_view"}{$LANG.view}{/if}
		{ if $subPageActive == "inventory_edit"}{$LANG.edit}{/if}
		{ if $subPageActive == "inventory_add"}{$LANG.add}{/if}
		{/if}
		{ if $pageActive == "setting"}{$LANG.settings}{/if}
		{ if $subPageActive == "setting_extensions"}{$LANG.extensions}{/if}
		{ if $pageActive == "system_default"}{$LANG.system_preferences}{/if}
		{ if $pageActive == "custom_field"}{$LANG.custom_fields_upper}{/if}
		{ if $subPageActive == "custom_fields_view"}{$LANG.view}{/if}
		{ if $subPageActive == "custom_fields_edit"}{$LANG.edit}{/if}
		{ if $pageActive == "tax_rate"}{$LANG.tax_rates}{/if}
		{ if $subPageActive == "tax_rates_add"}{$LANG.add}{/if}
		{ if $subPageActive == "tax_rates_view"}{$LANG.view}{/if}
		{ if $subPageActive == "tax_rates_edit"}{$LANG.edit}{/if}
		{ if $pageActive == "preference"}{$LANG.invoice_preferences}{/if}
		{ if $subPageActive == "preferences_add"}{$LANG.add}{/if}
		{ if $subPageActive == "preferences_view"}{$LANG.view}{/if}
		{ if $subPageActive == "preferences_edit"}{$LANG.edit}{/if}
		{ if $pageActive == "payment_type"}{$LANG.payment_types}{/if}
		{ if $subPageActive == "payment_types_add"}{$LANG.add}{/if}
		{ if $subPageActive == "payment_types_view"}{$LANG.view}{/if}
		{ if $subPageActive == "payment_types_edit"}{$LANG.edit}{/if}
		{ if $pageActive == "backup"}{$LANG.backup_database}{/if}
	</h1>
</div>

