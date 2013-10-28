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

