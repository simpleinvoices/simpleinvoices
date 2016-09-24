<div id="si_header">
{$smarty.capture.hook_topmenu_start}
{if $smarty.capture.hook_topmenu_section01_replace ne ""}
	{$smarty.capture.hook_topmenu_section01_replace}
{else}
	<div class="si_wrap">
		{$LANG.hello} {$smarty.session.Zend_Auth.email|htmlsafe} | <a href="http://www.simpleinvoices.org/help" target="blank">{$LANG.help}</a>
		{if $config->authentication->enabled == 1} |
			{if $smarty.session.Zend_Auth.id == null}
				<a href="index.php?module=auth&amp;view=login">{$LANG.login}</a>
			{else}
				<a href="index.php?module=auth&amp;view=logout">{$LANG.logout}</a>
				{if $smarty.session.Zend_Auth.domain_id != 1} | Domain: {$smarty.session.Zend_Auth.domain_id}{/if}
			{/if}
		{/if}
	</div>
{/if}
{$smarty.capture.hook_topmenu_end}
</div>
<div id="tabmenu" class="flora si_wrap" >
{$smarty.capture.hook_tabmenu_start}
	<ul>
		{$smarty.capture.hook_tabmenu_main_start}
		<li><a href="#home"><span>{$LANG.home}</span></a></li>
		<li><a href="#money"><span>{$LANG.money}</span></a></li>
		<li><a href="#people"><span>{$LANG.people}</span></a></li>
		<li><a href="#product"><span>{$LANG.products}</span></a></li>
		{$smarty.capture.hook_tabmenu_main_end}
		<li id="si_tab_settings"><a href="#setting"><span>{$LANG.settings}</span></a></li>
	</ul>
	<div id="home">
		<ul class="subnav">
<!-- SECTION:dashboard -->
			<li><a {if $pageActive== "dashboard"} class="active"{/if} href="index.php?module=index&amp;view=index">{$LANG.dashboard} </a></li>
<!-- SECTION:report -->
			<li><a {if $pageActive== "report"} class="active"{/if} href="index.php?module=reports&amp;view=index">{$LANG.all_reports} </a></li>
<!-- SECTION:END -->
		</ul>
	</div>
	<div id="money">
		<ul class="subnav">
<!-- SECTION:invoices -->
			<li><a {if $pageActive== "invoice"} class="active"{/if} href="index.php?module=invoices&amp;view=manage">{$LANG.invoices}</a></li>
			{if $subPageActive == "invoice_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			{if $subPageActive == "invoice_view"}<li><a class="active active_subpage" href="#">{$LANG.quick_view} </a></li>{/if}
<!-- SECTION:new_invoice -->
			<li><a {if $pageActive== "invoice_new"}class="active" {/if}id="invoice_dialogx" href="index.php?module=invoices&amp;view=itemised">{$LANG.new_invoice}</a></li>
			{if $subPageActive == "invoice_new_itemised"}<li><a class="active active_subpage" href="#">{$LANG.itemised}</a></li>{/if}
			{if $subPageActive == "invoice_new_total"}<li><a class="active active_subpage" href="#">{$LANG.total}</a></li>{/if}
<!-- SECTION:recurrence -->
			<li><a {if $pageActive== "cron"} class="active"{/if} href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a></li>
			{if $subPageActive == "cron_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{if $subPageActive == "cron_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			{if $subPageActive == "cron_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
<!-- SECTION:payments -->
			<li><a {if $pageActive== "payment"}class="active" {/if}href="index.php?module=payments&amp;view=manage">{$LANG.payments}</a></li>
			{if $subPageActive == "payment_process"}<li><a class="active active_subpage" href="#">{$LANG.process}</a></li>{/if}
			{if $subPageActive == "payment_eway"}<li><a class="active active_subpage" href="#">{$LANG.eway}</a></li>{/if}
			{if $subPageActive == "payment_filter_invoice"}<li><a class="active active_subpage" href="#">{$LANG.payments_filtered} {$preference.pref_inv_wording|htmlsafe} {$smarty.get.id|htmlsafe}</a></li>{/if}
			{if $subPageActive == "payment_filter_customer"}<li><a class="active active_subpage" href="#">{$LANG.payments_filtered_customer} '{$customer.name}'</a></li>{/if}
<!-- SECTION:sales_report -->
			<li><a {if $pageActive== "report_sale"}class="active" {/if}href="index.php?module=reports&amp;view=report_sales_total">{$LANG.sales_report}</a></li>
<!-- SECTION:END -->
		</ul>
	</div>
	<div id="people">
		<ul class="subnav">
<!-- SECTION:customers -->
			<li><a {if $pageActive== "customer"}class="active" {/if}href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a></li>
			{if $subPageActive == "customer_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{if $subPageActive == "customer_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
			{if $subPageActive == "customer_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
<!-- SECTION:billers -->
			<li><a {if $pageActive== "biller"}class="active" {/if}href="index.php?module=billers&amp;view=manage">{$LANG.billers}</a></li>
			{if $subPageActive == "biller_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{if $subPageActive == "biller_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
			{if $subPageActive == "biller_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
<!-- SECTION:users -->
			<li><a {if $pageActive== "user"}class="active" {/if}href="index.php?module=user&amp;view=manage">{$LANG.users}</a></li>
			{if $subPageActive == "user_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{if $subPageActive == "user_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
			{if $subPageActive == "user_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
<!-- SECTION:END -->
		</ul>
	</div>
	<div id="product">
		<ul class="subnav">
<!-- SECTION:manage_products -->
			<li><a {if $pageActive== "product_manage"}class="active" {/if}href="index.php?module=products&amp;view=manage">{$LANG.manage_products}</a></li>
			{if $subPageActive == "product_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
			{if $subPageActive == "product_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
<!-- SECTION:add_product -->
			<li><a {if $pageActive== "product_add"}class="active" {/if}href="index.php?module=products&amp;view=add">{$LANG.add_product}</a></li>
			{if $defaults.inventory == "1"}
				<li><a {if $pageActive== "inventory"}class="active" {/if}href="index.php?module=inventory&amp;view=manage">{$LANG.inventory}</a></li>
				{if $subPageActive == "inventory_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{if $subPageActive == "inventory_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
				{if $subPageActive == "inventory_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{/if}
<!-- SECTION:product_attributes -->
			{if $defaults.product_attributes}
				<li><a {if $pageActive== "inventory"}class="active" {/if}href="index.php?module=product_attribute&amp;view=manage">{$LANG.product_attributes}</a></li>
				{if $subPageActive == "inventory_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{if $subPageActive == "inventory_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
				{if $subPageActive == "inventory_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
				<li><a {if $pageActive== "inventory"}class="active" {/if}href="index.php?module=product_value&amp;view=manage">{$LANG.product_values}</a></li>
				{if $subPageActive == "inventory_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{if $subPageActive == "inventory_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
				{if $subPageActive == "inventory_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{/if}
<!-- SECTION:END -->
		</ul>
	</div>
	<div id="setting" style="float:right;" >
		<ul class="subnav">
<!-- SECTION:settings -->
			<li><a {if $pageActive== "setting"}class="active" {/if}href="index.php?module=options&amp;view=index">{$LANG.settings}</a></li>
			{if $subPageActive == "setting_extensions"}<li><a class="active active_subpage" href="#">{$LANG.extensions}</a></li>{/if}
<!-- SECTION:system_preferences -->
			<li><a {if $pageActive== "system_default"}class="active" {/if}href="index.php?module=system_defaults&amp;view=manage">{$LANG.system_preferences}</a></li>
<!-- SECTION:custom_fields -->
			<li><a {if $pageActive== "custom_field"}class="active" {/if}href="index.php?module=custom_fields&amp;view=manage">{$LANG.custom_fields_upper}</a></li>
			{if $subPageActive == "custom_fields_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
			{if $subPageActive == "custom_fields_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
<!-- SECTION:tax_rates -->
			<li><a {if $pageActive== "tax_rate"}class="active" {/if}href="index.php?module=tax_rates&amp;view=manage">{$LANG.tax_rates}</a></li>
			{if $subPageActive == "tax_rates_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{if $subPageActive == "tax_rates_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
			{if $subPageActive == "tax_rates_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
<!-- SECTION:invoice_preferences -->
			<li><a {if $pageActive== "preference"}class="active" {/if}href="index.php?module=preferences&amp;view=manage">{$LANG.invoice_preferences}</a></li>
			{if $subPageActive == "preferences_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{if $subPageActive == "preferences_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
			{if $subPageActive == "preferences_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
<!-- SECTION:payment_types -->
			<li><a {if $pageActive== "payment_type"}class="active" {/if}href="index.php?module=payment_types&amp;view=manage">{$LANG.payment_types}</a></li>
			{if $subPageActive == "payment_types_add"}<li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
			{if $subPageActive == "payment_types_view"}<li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
			{if $subPageActive == "payment_types_edit"}<li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			<li><a {if $pageActive== "backup"}class="active" {/if}href="index.php?module=options&amp;view=backup_database">{$LANG.backup_database}</a></li>
<!-- SECTION:END -->
		</ul>
	</div>
	{$smarty.capture.hook_tabmenu_end}
</div>
