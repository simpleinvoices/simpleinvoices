<div class="txt_right">
	Hello {$smarty.session.Zend_Auth.email} | <a href="">Help</a> | <a href="">Logout</a>
</div>
<!--
<div class="settings">
 <a href="index.php?module=options&view=index">Settings</a>
                <ul class="ui-tabs-nav">
					<li { if $pageActive == "setting"} class="ui-tabs-selected"{/if}><a href="index.php?module=options&view=index"><span>Settings</span></a></li>
				</ul>
</div>
-->

       <div id="tabmenu" class="flora">
            <ul>

                <li ><a href="#home"><span>Home</span></a></li>
                <li ><a href="#money"><span>Money</span></a></li>
                <li ><a href="#people"><span>People</span></a></li>
                <li ><a href="#product"><span>Products</span></a></li>
                <li style="float:right" class="menu_setting"><a href="#setting"><span>Settings</span></a></li>
            </ul>
            <div id="home">
				<ul class="subnav">
					<li><a { if $pageActive == "dashboard"} id="active" {/if} href="index.php">Dashboard</a></li>
					<li><a { if $pageActive == "report"} id="active" {/if} href="index.php?module=reports&view=index">All reports</a></li>
				</ul>
            </div>
            <div id="money">
				<ul class="subnav">
					<li><a { if $pageActive == "invoice"} id="active" {/if} href="index.php?module=invoices&view=manage">{$LANG.invoices}</a></li>
					<li><a { if $pageActive == "invoice_new"} id="active" {/if} id="invoice_dialog" href="#">New invoice</a></li> {*LANG TODO*}
					<li><a { if $pageActive == "payment"} id="active" {/if} id="invoice_export_dialog"  href="index.php?module=payments&view=manage">{$LANG.payments}</a></li>
					<li><a { if $pageActive == "report_sale"} id="active" {/if} href="index.php?module=reports&view=report_sales_total">Sales Report {*$LANG TODO*}</a></li>
				</ul>
            </div>
            <div id="people">
				<ul class="subnav">
					<li><a { if $pageActive == "customer"} id="active"{/if} href="index.php?module=customers&view=manage">{$LANG.customers}</a></li>
					<li><a { if $pageActive == "biller"} id="active" {/if} href="index.php?module=customers&view=manage">{$LANG.billers}</a></li>
					<li><a { if $pageActive == "user"} id="active" {/if} href="index.php?module=customers&view=add">Users {* $LANG TODO *}</a></li>
				</ul>
			</div>
           <div id="product">
                <ul class="subnav">
					<li><a { if $pageActive == "product_manage"} id="active"{/if} href="index.php?module=products&view=manage">{$LANG.manage_products}</a></li>
					<li><a { if $pageActive == "product_add"} id="active"{/if} href="index.php?module=products&view=add">{$LANG.add_product}</a></li>
				</ul>
            </div>
           <div style="float: right; " id="setting">
                <ul class="subnav">
					<li><a { if $pageActive == "setting"} id="active"{/if} href="index.php?module=options&view=index">All settings {* LANG TODO *}</a></li>
					<li><a { if $pageActive == "system_default"} id="active"{/if} href="index.php?module=system_defaults&view=manage">{$LANG.system_preferences}</a></li>
					<li><a { if $pageActive == "custom_field"} id="active"{/if} href="index.php?module=custom_fields&view=manage">{$LANG.custom_fields_upper}</a></li>
					<li><a { if $pageActive == "tax_rate"} id="active"{/if} href="index.php?module=tax_rates&view=manage">{$LANG.tax_rates}</a></li>
					<li><a { if $pageActive == "preference"} id="active"{/if} href="index.php?module=preferences&view=manage">{$LANG.invoice_preferences}</a></li>
					<li><a { if $pageActive == "payment_type"} id="active"{/if} href="index.php?module=payment_types&view=manage">{$LANG.payment_types}</a></li>
					<li><a { if $pageActive == "backup"} id="active"{/if} href="index.php?module=options&view=backup_database">{$LANG.backup_database}</a></li>
					<li><a { if $pageActive == "sqlpatch"} id="active"{/if} href="index.php?module=options&view=manage_sqlpatches">{$LANG.database_upgrade_manager}</a></li>
				</ul>
            </div>
        </div>
        
        
<div id="dialog" class="flora" title="Please select Invoice type">
	<a href="index.php?module=invoices&view=total"> Total style</a>
	<br>
	<a href="index.php?module=invoices&view=itemised"> Itemised style</a>
	<br>
	<a href="index.php?module=invoices&view=consulting"> Consulting style</a>
</div>

<div id="export_dialog" class="flora" title="EXPORTlect Invoice type">

		<!--3 EXPORT TO PDF --><a title='".$LANG['export_tooltip']." ".$invoice['preference.pref_inv_wording']." ".$row['id']." ".$LANG['export_pdf_tooltip']."' class='export_pdf' href=''><img src='images/common/page_white_acrobat.png' height='16' padding='-4px' border='-5px' valign='bottom' /><!-- pdf --></a>
		<!--4 XLS --><a title='".$LANG['export_tooltip']." ".$invoice['preference.pref_inv_wording']." ".$row['id']." ".$LANG['export_xls_tooltip'].$spreadsheet." ".$LANG['format_tooltip']."' class='export_xls' href='index.php?module=invoices&view=templates/template&invoice='".$row['id']."&action=view&location=print&export=".$spreadsheet."'><img src='images/common/page_white_excel.png' height='16' border='0' padding='-4px' valign='bottom' /><!-- $spreadsheet --></a>
	<!--4 XLS --><a title='".$LANG['export_tooltip']." ".$invoice['preference.pref_inv_wording']." ".$row['id']." ".$LANG['export_xls_tooltip'].$spreadsheet." ".$LANG['format_tooltip']."' class='export_doc' href='index.php?module=invoices&view=templates/template&invoice='".$row['id']."&action=view&location=print&export=".$spreadsheet."'><img src='images/common/page_white_excel.png' height='16' border='0' padding='-4px' valign='bottom' /><!-- $spreadsheet --></a>


	<a href="index.php?module=invoices&view=total"> Total style</a>
	<br>
	<a href="index.php?module=invoices&view=itemised"> Itemised style</a>
	<br>
	<a href="index.php?module=invoices&view=consulting"> Consulting style</a>
</div>



<!--
########
OLD MENU
########
<div id="Header">
		<ul class="">
			<li { if $pageActive == null} id="active" {/if}><a href="index.php">{$LANG.home}</a></li>
			<li { if $pageActive == 'invoices'} id="active" {/if}><a href="index.php?module=invoices&view=manage">{$LANG.invoices} +</a>
				<ul>
					<li><a href="index.php?module=invoices&view=manage">{$LANG.manage_invoices}</a></li>
					<li class="separation"></li>
					<li><a href="index.php?module=invoices&view=total">{$LANG.new_invoice_total}</a></li>
					<li><a href="index.php?module=invoices&view=itemised">{$LANG.new_invoice_itemised}</a></li>
					<li><a href="index.php?module=invoices&view=consulting">{$LANG.new_invoice_consulting}</a></li>
					{*
					<li class="separation"></li>
					<li><a href="index.php?module=invoices&view=search">Search invoices</a></li>
					*}
				</ul>
			</li>
			<li { if $pageActive == 'customers'} id="active" {/if} ><a href="index.php?module=customers&view=manage">{$LANG.customers} +</a>
				<ul>
					<li><a href="index.php?module=customers&view=manage">{$LANG.manage_customers}</a></li>
					<li><a href="index.php?module=customers&view=add">{$LANG.add_customer}</a></li>
					{*
					<li class="separation"></li>
					<li><a href="index.php?module=customers&view=search">Search customer</a></li>
					*}
				</ul>
			</li>
			<li { if $pageActive == 'products'} id="active" {/if} ><a href="index.php?module=products&view=manage">{$LANG.products} +</a>
				<ul>
					<li><a href="index.php?module=products&view=manage">{$LANG.manage_products}</a></li>
					<li><a href="index.php?module=products&view=add">{$LANG.add_product}</a></li>
				</ul>
			</li>
			<li { if $pageActive == 'billers'} id="active" {/if}> <a href="index.php?module=billers&view=manage">{$LANG.billers} +</a>
				<ul>
					<li><a href="index.php?module=billers&view=manage">{$LANG.manage_billers}</a></li>
					<li><a href="index.php?module=billers&view=add">{$LANG.add_biller}</a></li>
				</ul>
			</li>
			<li { if $pageActive == 'payments'} id="active" {/if}> <a href="index.php?module=payments&view=manage">{$LANG.payments} +</a>
				<ul>
					<li><a href="index.php?module=payments&view=manage">{$LANG.manage_payments}</a></li>
					<li><a href="index.php?module=payments&view=process&op=pay_invoice">{$LANG.process_payment}</a></li>
				</ul>
			</li>
			<li { if $pageActive == 'reports'} id="active" {/if} ><a href="#">{$LANG.reports} +</a>
				<ul>
					<li><a href="index.php?module=reports&view=report_sales_total">{$LANG.sales} +</a>
						<ul>
							<li><a href="index.php?module=reports&view=report_sales_total">{$LANG.total_sales}</a></li>
							<li><a href="index.php?module=reports&view=report_sales_by_periods">{$LANG.monthly_sales_per_year}</a></li>
						</ul>
					</li>
					<li><a href="index.php?module=reports&view=report_sales_customers_total">{$LANG.sales_by_customers} +</a>
						<ul>
							<li><a href="./index.php?module=reports&view=report_sales_customers_total">{$LANG.total_sales_by_customer}</a>
							</li>
						</ul>
					</li>
					<li><a href="./index.php?module=reports&view=report_tax_total">{$LANG.tax} +</a>
						<ul>
							<li><a href="./index.php?module=reports&view=report_tax_total">{$LANG.total_taxes}</a></li>
						</ul>
					</li>
					<li><a href="index.php?module=reports&view=report_products_sold_total">{$LANG.product_sales} +</a>
						<ul>
							<li><a href="./index.php?module=reports&view=report_products_sold_total">{$LANG.products_sold_total}</a>
							</li>
						</ul>
					</li>
					<li><a href="./index.php?module=reports&view=report_products_sold_by_customer">{$LANG.products_by_customer} +</a>
						<ul>
							<li><a href="./index.php?module=reports&view=report_products_sold_by_customer">{$LANG.products_sold_customer_total}</a>
							</li>
						</ul>
					</li>
					<li><a href="index.php?module=reports&view=report_biller_total">{$LANG.biller_sales} +</a>
						<ul>
							<li><a href="index.php?module=reports&view=report_biller_total">{$LANG.biller_sales_total}</a></li>
							<li><a href="./index.php?module=reports&view=report_biller_by_customer">{$LANG.biller_sales_by_customer_totals}</a>
							</li>
						</ul>
					</li>
					<li><a href="./index.php?module=reports&view=report_debtors_by_amount">{$LANG.debtors} +</a>
						<ul>
							<li><a href="./index.php?module=reports&view=report_debtors_by_amount">{$LANG.debtors_by_amount_owed}</a>
							</li>
							<li><a href="./index.php?module=reports&view=report_debtors_by_aging">{$LANG.debtors_by_aging_periods}</a>
							</li>
							<li><a href="./index.php?module=reports&view=report_debtors_owing_by_customer">{$LANG.total_owed_per_customer}</a>
							</li>
							<li><a href="./index.php?module=reports&view=report_debtors_aging_total">{$LANG.total_by_aging_periods}</a>
							</li>
						</ul>
					</li>
					<li class="separation"></li>
					<li><a href="./index.php?module=reports&view=database_log">Database Log</a></li>
				</ul>
			</li>
			<li  { if $pageActive == 'options'} id="active" {/if} ><a href="#">{$LANG.options} +</a>
				<ul>
					<li>
						<a href="./index.php?module=system_defaults&view=manage">{$LANG.system_preferences}</a>
					</li>
					<li>
						<a href="./index.php?module=custom_fields&view=manage">{$LANG.custom_fields_upper}</a>
					</li>
					{*
					<li>
						<a href="./index.php?module=customFields&view=manage">{$LANG.custom_fields_upper} 2</a>
					</li>
					*}
					<li class="separation"></li>
					<li>
						<a href="./index.php?module=tax_rates&view=manage">{$LANG.tax_rates} +</a>
						<ul>
							<li>
								<a href="./index.php?module=tax_rates&view=manage">{$LANG.manage_tax_rates}</a>
							</li>
							<li>
								<a href="./index.php?module=tax_rates&view=add">{$LANG.add_tax_rate}</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="./index.php?module=preferences&view=manage">{$LANG.invoice_preferences} +</a>
						<ul>
							<li>
								<a href="./index.php?module=preferences&view=manage">{$LANG.manage_invoice_preferences}</a>
							</li>
							<li>
								<a href="./index.php?module=preferences&view=add">{$LANG.add_invoice_preference}</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="./index.php?module=payment_types&view=manage">{$LANG.payment_types} +</a>
						<ul>
							<li>
								<a href="./index.php?module=payment_types&view=manage">{$LANG.manage_payment_types}</a>
							</li>
							<li>
								<a href="./index.php?module=payment_types&view=add">{$LANG.add_payment_type}</a>
							</li>
						</ul>
					</li>
					<li class="separation"></li>
					<li>
						<a href="./index.php?module=options&view=manage_sqlpatches">{$LANG.database_upgrade_manager}</a>
					</li>
					<li>
						<a href="./index.php?module=options&view=backup_database">{$LANG.backup_database}</a>
					</li>

				</ul>
			</li>
			<li> <a href="#"><img src="./images/common/help-small.png"></a>
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
							<li><a href="index.php?module=options&view=help">{$LANG.get_help}</a></li>
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
					<li><a href="index.php?module=auth&view=login">{$LANG.login}</a></li>
				{else}
					<li><a href="index.php?module=auth&view=logout">{$LANG.logout}</a></li>
				{/if}
			{/if}
		</ul>
</div>

-->
