{$smarty.capture.hook_tabmenu_start}

<aside class="navbar navbar-vertical navbar-expand-md">
	<div class="collapse navbar-collapse" id="sidebar-menu">
	<div class="navbar-nav">
		<div class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
				<span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-home"></i></span>
				<span class="nav-link-title">{$LANG.home}</span>
			</a>
			<div class="dropdown-menu">
				<a class="dropdown-item {if $pageActive == "dashboard"}active{/if}" href="index.php?module=index&amp;view=index">{$LANG.dashboard}</a>
				<a class="dropdown-item {if $pageActive == "report"}active{/if}" href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a>
			</div>
		</div>
		<div class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
				<span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-currency-dollar"></i></span>
				<span class="nav-link-title">{$LANG.money}</span>
			</a>
			<div class="dropdown-menu">
				<a class="dropdown-item {if $pageActive == "invoice"}active{/if}" href="index.php?module=invoices&amp;view=manage">{$LANG.invoices}</a>
				<a class="dropdown-item {if $pageActive == "invoice_new"}active{/if}" href="index.php?module=invoices&amp;view=itemised">{$LANG.new_invoice}</a>
				<a class="dropdown-item {if $pageActive == "cron"}active{/if}" href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a>
				<a class="dropdown-item {if $pageActive == "payment"}active{/if}" href="index.php?module=payments&amp;view=manage">{$LANG.payments}</a>
				<a class="dropdown-item {if $pageActive == "report_sale"}active{/if}" href="index.php?module=reports&amp;view=report_sales_total">{$LANG.sales_report}</a>
			</div>
		</div>
		<div class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
				<span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-users"></i></span>
				<span class="nav-link-title">{$LANG.people}</span>
			</a>
			<div class="dropdown-menu">
				<a class="dropdown-item {if $pageActive == "customer"}active{/if}" href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a>
				<a class="dropdown-item {if $pageActive == "biller"}active{/if}" href="index.php?module=billers&amp;view=manage">{$LANG.billers}</a>
				<a class="dropdown-item {if $pageActive == "user"}active{/if}" href="index.php?module=user&amp;view=manage">{$LANG.users}</a>
			</div>
		</div>
		<div class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
				<span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-package"></i></span>
				<span class="nav-link-title">{$LANG.products}</span>
			</a>
			<div class="dropdown-menu">
				<a class="dropdown-item {if $pageActive == "product_manage"}active{/if}" href="index.php?module=products&amp;view=manage">{$LANG.manage_products}</a>
				<a class="dropdown-item {if $pageActive == "product_add"}active{/if}" href="index.php?module=products&amp;view=add">{$LANG.add_product}</a>
				{if isset($defaults.inventory) && $defaults.inventory == "1"}
				<a class="dropdown-item {if $pageActive == "inventory"}active{/if}" href="index.php?module=inventory&amp;view=manage">{$LANG.inventory}</a>
				{/if}
				{if isset($defaults.product_attributes) && $defaults.product_attributes}
				<a class="dropdown-item" href="index.php?module=product_attribute&amp;view=manage">{$LANG.product_attributes}</a>
				<a class="dropdown-item" href="index.php?module=product_value&amp;view=manage">{$LANG.product_values}</a>
				{/if}
			</div>
		</div>
		<div class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
				<span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-settings"></i></span>
				<span class="nav-link-title">{$LANG.settings}</span>
			</a>
			<div class="dropdown-menu">
				<a class="dropdown-item {if $pageActive == "setting"}active{/if}" href="index.php?module=options&amp;view=index">{$LANG.settings}</a>
				<a class="dropdown-item {if $pageActive == "system_default"}active{/if}" href="index.php?module=system_defaults&amp;view=manage">{$LANG.system_preferences}</a>
				<a class="dropdown-item {if $pageActive == "custom_field"}active{/if}" href="index.php?module=custom_fields&amp;view=manage">{$LANG.custom_fields_upper}</a>
				<a class="dropdown-item {if $pageActive == "tax_rate"}active{/if}" href="index.php?module=tax_rates&amp;view=manage">{$LANG.tax_rates}</a>
				<a class="dropdown-item {if $pageActive == "preference"}active{/if}" href="index.php?module=preferences&amp;view=manage">{$LANG.invoice_preferences}</a>
				<a class="dropdown-item {if $pageActive == "payment_type"}active{/if}" href="index.php?module=payment_types&amp;view=manage">{$LANG.payment_types}</a>
				<a class="dropdown-item {if $pageActive == "backup"}active{/if}" href="index.php?module=options&amp;view=backup_database">{$LANG.backup_database}</a>
			</div>
		</div>
	</div>
	</div>
</aside>

{$smarty.capture.hook_tabmenu_end}
