<div class="top_menu">
{*
	<div class="txt_right" style="">
		<table class="buttons" align="left">
		<tr>
		<td>
			<a href="./index.php?module=billers&amp;view=add" class="positive">
			<img src="./images/famfam/add.png" alt="" />
			</a>
		</td>
		</tr>
		</table>
	</div>
*}
	<div class="txt_right">
		Hello {$smarty.session.Zend_Auth.email} | <a href="">Help</a>
		{if $config->authentication->enabled == 1}
			|
			{if $smarty.session.Zend_Auth.id == null}
				<a href="index.php?module=auth&amp;view=login">{$LANG.login}</a>
			{else}
				<a href="index.php?module=auth&amp;view=logout">{$LANG.logout}</a>
			{/if}
		{/if}
	
	</div>
</div>

<div id="tabmenu" class="flora" >
	<ul>
		<li><a href="#home"><span>{$LANG.home}</span></a></li>
		<li><a href="#money"><span>{$LANG.money}</span></a></li>
		<li><a href="#people"><span>{$LANG.people}</span></a></li>
		<li><a href="#product"><span>{$LANG.products}</span></a></li>
		<li style="float:right" class="menu_setting"><a href="#setting"><span>{$LANG.settings}</span></a></li>
	</ul>

	<div id="home">
		<ul class="subnav">
			<li><a { if $pageActive == "dashboard"} class="active" {/if} href="index.php?module=index&amp;view=index">{$LANG.dashboard}</a></li>
			<li><a { if $pageActive == "report"} class="active" {/if} href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a></li>
		</ul>
	</div>

	<div id="money">
		<ul class="subnav">
			<li><a { if $pageActive == "invoice"} class="active" {/if} href="index.php?module=invoices&amp;view=manage">{$LANG.invoices}</a></li>
				{ if $subPageActive == "invoice_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
				{ if $subPageActive == "invoice_view"} <li><a class="active active_subpage" href="#">{$LANG.quick_view}</a></li>{/if}
				{* dialog style <li><a { if $pageActive == "invoice_new"} class="active" {/if} id="invoice_dialog" href="#">{$LANG.new_invoice}</a></li> *}
			<li><a { if $pageActive == "invoice_new"} class="active" {/if} id="invoice_dialogx" href="index.php?module=invoices&amp;view=itemised">{$LANG.new_invoice}</a></li> 
				{ if $subPageActive == "invoice_new_itemised"} <li><a class="active active_subpage" href="#">{$LANG.itemised}</a></li>{/if}				
				{ if $subPageActive == "invoice_new_total"} <li><a class="active active_subpage" href="#">{$LANG.total}</a></li>{/if}				
			<li><a { if $pageActive == "expense"} class="active" {/if} href="index.php?module=expense&amp;view=manage">{$LANG.expense}</a></li>
                { if $pageActive == "expense"}
                    { if $subPageActive == "edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    { if $subPageActive == "view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                    { if $subPageActive == "add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
                {/if}
			<li><a { if $pageActive == "expense_account"} class="active" {/if} href="index.php?module=expense_account&amp;view=manage">{$LANG.accounts}</a></li>
                { if $pageActive == "expense_account"}
                    { if $subPageActive == "edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    { if $subPageActive == "view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                    { if $subPageActive == "add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
                { /if }
			<li><a { if $pageActive == "payment"} class="active" {/if} href="index.php?module=payments&amp;view=manage">{$LANG.payments}</a></li>
				{ if $subPageActive == "payment_process"} <li><a class="active active_subpage" href="#">{$LANG.process}</a></li>{/if}
				{ if $subPageActive == "payment_filter_invoice"} <li><a class="active active_subpage" href="#">{$LANG.payments_filtered} {$preference.pref_inv_wording} {$smarty.get.id}</a></li>{/if}
				{ if $subPageActive == "payment_filter_customer"} <li><a class="active active_subpage" href="#">{$LANG.payments_filtered_customer} '{$customer.name}'</a></li>{/if}
			<li><a { if $pageActive == "report_sale"} class="active" {/if} href="index.php?module=reports&amp;view=report_sales_total">{$LANG.sales_report}</a></li>
		</ul>
	</div>

	<div id="people">
		<ul class="subnav">
			<li><a { if $pageActive == "customer"} class="active"{/if} href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a></li>
				{ if $subPageActive == "customer_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
				{ if $subPageActive == "customer_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{ if $subPageActive == "customer_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			<li><a { if $pageActive == "biller"} class="active" {/if} href="index.php?module=billers&amp;view=manage">{$LANG.billers}</a></li>
				{ if $subPageActive == "biller_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
				{ if $subPageActive == "biller_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{ if $subPageActive == "biller_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			<li><a { if $pageActive == "user"} class="active" {/if} href="index.php?module=user&amp;view=manage">{$LANG.users}</a></li>
				{ if $subPageActive == "user_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
				{ if $subPageActive == "user_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{ if $subPageActive == "user_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
		</ul>
	</div>

	<div id="product">
                <ul class="subnav">
			<li><a { if $pageActive == "product_manage"} class="active"{/if} href="index.php?module=products&amp;view=manage">{$LANG.manage_products}</a></li>
				{ if $subPageActive == "product_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{ if $subPageActive == "product_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			<li><a { if $pageActive == "product_add"} class="active"{/if} href="index.php?module=products&amp;view=add">{$LANG.add_product}</a></li>
            {if $defaults.inventory == "1"}
    			<li><a { if $pageActive == "inventory"} class="active"{/if} href="index.php?module=inventory&amp;view=manage">{$LANG.inventory}</a></li>
	    			{ if $subPageActive == "inventory_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
		    		{ if $subPageActive == "inventory_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			    	{ if $subPageActive == "inventory_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {/if}
		</ul>
	</div>

	<div style="float: right; " id="setting">
		<ul class="subnav">
			<li><a { if $pageActive == "setting"} class="active"{/if} href="index.php?module=options&amp;view=index">{$LANG.settings}</a></li>
				{ if $subPageActive == "setting_extensions"} <li><a class="active active_subpage" href="#">{$LANG.extensions}</a></li>{/if}
			<li><a { if $pageActive == "system_default"} class="active"{/if} href="index.php?module=system_defaults&amp;view=manage">{$LANG.system_preferences}</a></li>
			<li><a { if $pageActive == "custom_field"} class="active"{/if} href="index.php?module=custom_fields&amp;view=manage">{$LANG.custom_fields_upper}</a></li>
				{ if $subPageActive == "custom_fields_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{ if $subPageActive == "custom_fields_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			<li><a { if $pageActive == "tax_rate"} class="active"{/if} href="index.php?module=tax_rates&amp;view=manage">{$LANG.tax_rates}</a></li>
				{ if $subPageActive == "tax_rates_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
				{ if $subPageActive == "tax_rates_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{ if $subPageActive == "tax_rates_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			<li><a { if $pageActive == "preference"} class="active"{/if} href="index.php?module=preferences&amp;view=manage">{$LANG.invoice_preferences}</a></li>
				{ if $subPageActive == "preferences_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
				{ if $subPageActive == "preferences_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{ if $subPageActive == "preferences_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			<li><a { if $pageActive == "payment_type"} class="active"{/if} href="index.php?module=payment_types&amp;view=manage">{$LANG.payment_types}</a></li>
				{ if $subPageActive == "payment_types_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
				{ if $subPageActive == "payment_types_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
				{ if $subPageActive == "payment_types_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
			<li><a { if $pageActive == "backup"} class="active"{/if} href="index.php?module=options&amp;view=backup_database">{$LANG.backup_database}</a></li>
		</ul>
	</div>
</div>

