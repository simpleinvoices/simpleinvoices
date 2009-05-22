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

<!--
<div class="settings">
 <a href="index.php?module=options&amp;view=index">Settings</a>
                <ul class="ui-tabs-nav">
					<li { if $pageActive == "setting"} class="ui-tabs-selected"{/if}><a href="index.php?module=options&amp;view=index"><span>Settings</span></a></li>
				</ul>
</div>
-->

       <div id="tabmenu" class="flora" >
            <ul>

                <li ><a href="#home"><span>{$LANG.home}</span></a></li>
                <li ><a href="#money"><span>{$LANG.money}</span></a></li>
                <li ><a href="#people"><span>{$LANG.people}</span></a></li>
                <li ><a href="#product"><span>{$LANG.products}</span></a></li>
                <li style="float:right" class="menu_setting"><a href="#setting"><span>{$LANG.settings}</span></a></li>
            </ul>
            <div id="home">
				<ul class="subnav">
					<li><a { if $pageActive == "dashboard"} class="active" {/if} href="index.php">{$LANG.dashboard}</a></li>
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
					<li><a { if $pageActive == "payment"} class="active" {/if} href="index.php?module=payments&amp;view=manage">{$LANG.payments}</a></li>
					{ if $subPageActive == "payment_process"} <li><a class="active active_subpage" href="#">{$LANG.process}</a></li>{/if}
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
				</ul>
            </div>
           <div style="float: right; " id="setting">
                <ul class="subnav">
					<li><a { if $pageActive == "setting"} class="active"{/if} href="index.php?module=options&amp;view=index">{$LANG.settings}</a></li>
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
        
        
	
	
	
</div>



<!--
########
OLD MENU
########
<div id="Header">
		<ul class="">
			<li { if $pageActive == null} id="active" {/if}><a href="index.php">{$LANG.home}</a></li>
			<li { if $pageActive == 'invoices'} id="active" {/if}><a href="index.php?module=invoices&amp;view=manage">{$LANG.invoices} +</a>
				<ul>
					<li><a href="index.php?module=invoices&amp;view=manage">{$LANG.manage_invoices}</a></li>
					<li class="separation"></li>
					<li><a href="index.php?module=invoices&amp;view=total">{$LANG.new_invoice_total}</a></li>
					<li><a href="index.php?module=invoices&amp;view=itemised">{$LANG.new_invoice_itemised}</a></li>
					<li><a href="index.php?module=invoices&amp;view=consulting">{$LANG.new_invoice_consulting}</a></li>
					{*
					<li class="separation"></li>
					<li><a href="index.php?module=invoices&amp;view=search">Search invoices</a></li>
					*}
				</ul>
			</li>
			<li { if $pageActive == 'customers'} id="active" {/if} ><a href="index.php?module=customers&amp;view=manage">{$LANG.customers} +</a>
				<ul>
					<li><a href="index.php?module=customers&amp;view=manage">{$LANG.manage_customers}</a></li>
					<li><a href="index.php?module=customers&amp;view=add">{$LANG.add_customer}</a></li>
					{*
					<li class="separation"></li>
					<li><a href="index.php?module=customers&amp;view=search">Search customer</a></li>
					*}
				</ul>
			</li>
			<li { if $pageActive == 'products'} id="active" {/if} ><a href="index.php?module=products&amp;view=manage">{$LANG.products} +</a>
				<ul>
					<li><a href="index.php?module=products&amp;view=manage">{$LANG.manage_products}</a></li>
					<li><a href="index.php?module=products&amp;view=add">{$LANG.add_product}</a></li>
				</ul>
			</li>
			<li { if $pageActive == 'billers'} id="active" {/if}> <a href="index.php?module=billers&amp;view=manage">{$LANG.billers} +</a>
				<ul>
					<li><a href="index.php?module=billers&amp;view=manage">{$LANG.manage_billers}</a></li>
					<li><a href="index.php?module=billers&amp;view=add">{$LANG.add_biller}</a></li>
				</ul>
			</li>
			<li { if $pageActive == 'payments'} id="active" {/if}> <a href="index.php?module=payments&amp;view=manage">{$LANG.payments} +</a>
				<ul>
					<li><a href="index.php?module=payments&amp;view=manage">{$LANG.manage_payments}</a></li>
					<li><a href="index.php?module=payments&amp;view=process&op=pay_invoice">{$LANG.process_payment}</a></li>
				</ul>
			</li>
			<li { if $pageActive == 'reports'} id="active" {/if} ><a href="#">{$LANG.reports} +</a>
				<ul>
					<li><a href="index.php?module=reports&amp;view=report_sales_total">{$LANG.sales} +</a>
						<ul>
							<li><a href="index.php?module=reports&amp;view=report_sales_total">{$LANG.total_sales}</a></li>
							<li><a href="index.php?module=reports&amp;view=report_sales_by_periods">{$LANG.monthly_sales_per_year}</a></li>
						</ul>
					</li>
					<li><a href="index.php?module=reports&amp;view=report_sales_customers_total">{$LANG.sales_by_customers} +</a>
						<ul>
							<li><a href="./index.php?module=reports&amp;view=report_sales_customers_total">{$LANG.total_sales_by_customer}</a>
							</li>
						</ul>
					</li>
					<li><a href="./index.php?module=reports&amp;view=report_tax_total">{$LANG.tax} +</a>
						<ul>
							<li><a href="./index.php?module=reports&amp;view=report_tax_total">{$LANG.total_taxes}</a></li>
						</ul>
					</li>
					<li><a href="index.php?module=reports&amp;view=report_products_sold_total">{$LANG.product_sales} +</a>
						<ul>
							<li><a href="./index.php?module=reports&amp;view=report_products_sold_total">{$LANG.products_sold_total}</a>
							</li>
						</ul>
					</li>
					<li><a href="./index.php?module=reports&amp;view=report_products_sold_by_customer">{$LANG.products_by_customer} +</a>
						<ul>
							<li><a href="./index.php?module=reports&amp;view=report_products_sold_by_customer">{$LANG.products_sold_customer_total}</a>
							</li>
						</ul>
					</li>
					<li><a href="index.php?module=reports&amp;view=report_biller_total">{$LANG.biller_sales} +</a>
						<ul>
							<li><a href="index.php?module=reports&amp;view=report_biller_total">{$LANG.biller_sales_total}</a></li>
							<li><a href="./index.php?module=reports&amp;view=report_biller_by_customer">{$LANG.biller_sales_by_customer_totals}</a>
							</li>
						</ul>
					</li>
					<li><a href="./index.php?module=reports&amp;view=report_debtors_by_amount">{$LANG.debtors} +</a>
						<ul>
							<li><a href="./index.php?module=reports&amp;view=report_debtors_by_amount">{$LANG.debtors_by_amount_owed}</a>
							</li>
							<li><a href="./index.php?module=reports&amp;view=report_debtors_by_aging">{$LANG.debtors_by_aging_periods}</a>
							</li>
							<li><a href="./index.php?module=reports&amp;view=report_debtors_owing_by_customer">{$LANG.total_owed_per_customer}</a>
							</li>
							<li><a href="./index.php?module=reports&amp;view=report_debtors_aging_total">{$LANG.total_by_aging_periods}</a>
							</li>
						</ul>
					</li>
					<li class="separation"></li>
					<li><a href="./index.php?module=reports&amp;view=database_log">Database Log</a></li>
				</ul>
			</li>
			<li  { if $pageActive == 'options'} id="active" {/if} ><a href="#">{$LANG.options} +</a>
				<ul>
					<li>
						<a href="./index.php?module=system_defaults&amp;view=manage">{$LANG.system_preferences}</a>
					</li>
					<li>
						<a href="./index.php?module=custom_fields&amp;view=manage">{$LANG.custom_fields_upper}</a>
					</li>
					{*
					<li>
						<a href="./index.php?module=customFields&amp;view=manage">{$LANG.custom_fields_upper} 2</a>
					</li>
					*}
					<li class="separation"></li>
					<li>
						<a href="./index.php?module=tax_rates&amp;view=manage">{$LANG.tax_rates} +</a>
						<ul>
							<li>
								<a href="./index.php?module=tax_rates&amp;view=manage">{$LANG.manage_tax_rates}</a>
							</li>
							<li>
								<a href="./index.php?module=tax_rates&amp;view=add">{$LANG.add_tax_rate}</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="./index.php?module=preferences&amp;view=manage">{$LANG.invoice_preferences} +</a>
						<ul>
							<li>
								<a href="./index.php?module=preferences&amp;view=manage">{$LANG.manage_invoice_preferences}</a>
							</li>
							<li>
								<a href="./index.php?module=preferences&amp;view=add">{$LANG.add_invoice_preference}</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="./index.php?module=payment_types&amp;view=manage">{$LANG.payment_types} +</a>
						<ul>
							<li>
								<a href="./index.php?module=payment_types&amp;view=manage">{$LANG.manage_payment_types}</a>
							</li>
							<li>
								<a href="./index.php?module=payment_types&amp;view=add">{$LANG.add_payment_type}</a>
							</li>
						</ul>
					</li>
					<li class="separation"></li>
					<li>
						<a href="./index.php?module=options&amp;view=manage_sqlpatches">{$LANG.database_upgrade_manager}</a>
					</li>
					<li>
						<a href="./index.php?module=options&amp;view=backup_database">{$LANG.backup_database}</a>
					</li>

				</ul>
			</li>
			<li> <a href="#"><img src="./images/common/help-small.png" alt="" /></a>
				<ul>
					<li>
						<a href="docs.php?p=ReadMe">{$LANG.help} +</a>
						<ul>
							<li>
								<a href="docs.php?p=ReadMe#installation">{$LANG.installation}</a>
							</li>
							<li>
								<a href="docs.php?p=ReadMe#upgrading">{$LANG.upgrading_simple_invoices}</a>
							</li>
							<li><a href="docs.php?p=ReadMe#prepare">{$LANG.prepare_simple_invoices}</a>
							</li>
							<li><a href="docs.php?p=ReadMe#use">{$LANG.using_simple_invoices}</a>
							</li>
							<li><a href="docs.php?p=ReadMe#faqs">{$LANG.faqs}</a></li>
							<li><a href="index.php?module=options&amp;view=help">{$LANG.get_help}</a></li>
						</ul>
					</li>
					<li><a href="docs.php?p=about">{$LANG.about} +</a>
						<ul>
							<li><a href="docs.php?p=about">{$LANG.about}</a></li>
							<li><a href="docs.php?p=ChangeLog">{$LANG.change_log}</a></li>
							<li><a href="docs.php?p=Credits">{$LANG.credits}</a></li>
							<li><a href="docs.php?p=gpl">{$LANG.license}</a></li>
						</ul>
					</li>
				</ul>
			</li>
			{if $config->authentication->enabled == 1}
				{if $smarty.session.Zend_Auth.user_id == null}
					<li><a href="index.php?module=auth&amp;view=login">{$LANG.login}</a></li>
				{else}
					<li><a href="index.php?module=auth&amp;view=logout">{$LANG.logout}</a></li>
				{/if}
			{/if}
		</ul>
</div>

-->
