<div id="Header">
		<ul class="adxm menu">
			<li { if $pageActive == null} id="active" {/if}><a href="index.php">{$LANG.home}</a></li>
			<li { if $pageActive == 'invoices'} id="active" {/if}><a href="index.php?module=invoices&view=manage">{$LANG.invoices} +</a>
				<ul>
					<li><a href="index.php?module=invoices&view=manage">{$LANG.manage_invoices}</a></li>
					<li class="separation"></li>
					<li><a href="index.php?module=invoices&view=total">{$LANG.new_invoice_total}</a></li>
					<li><a href="index.php?module=invoices&view=itemised">{$LANG.new_invoice_itemised}</a></li>
					<li><a href="index.php?module=invoices&view=consulting">{$LANG.new_invoice_consulting}</a></li>
					<li class="separation"></li>
					<li><a href="index.php?module=invoices&view=search">Search invoices</a></li>
				</ul>
			</li>
			<li { if $pageActive == 'customers'} id="active" {/if} ><a href="index.php?module=customers&view=manage">{$LANG.customers} +</a>
				<ul>
					<li><a href="index.php?module=customers&view=manage">{$LANG.manage_customers}</a></li>
					<li><a href="index.php?module=customers&view=add">{$LANG.add_customer}</a></li>
					<li class="separation"></li>
					<li><a href="index.php?module=customers&view=search">Search customer</a></li>
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
					</li>
					<li class="separation"></li>
					<li>
						<a href="./index.php?module=product_attribute&view=manage">Product Attributes +</a>
						<ul>
							<li>
								<a href="./index.php?module=product_attribute&view=manage">Manage Attributes</a>
							</li>
							<li>
								<a href="./index.php?module=product_attribute&view=add">Add Attribute</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="./index.php?module=product_value&view=manage">Product Values +</a>
						<ul>
							<li>
								<a href="./index.php?module=product_value&view=manage">Manage Values</a>
							</li>
							<li>
								<a href="./index.php?module=product_value&view=add">Add Values</a>
							</li>
						</ul>
					</li>
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
					<li><a href="index.php?module=documentation/inline_docs&view=about">{$LANG.about} +</a>
						<ul>
							<li><a href="docs.php?p=about">{$LANG.about}</a></li>
							<li><a href="docs.php?p=ChangeLog">{$LANG.change_log}</a></li>
							<li><a href="docs.php?p=Credits">{$LANG.credits}</a></li>
							<li><a href="docs.php?p=gpl">{$LANG.license}</a></li>
						</ul>
					</li>
				</ul>
			</li>
			{if $config->authentication->enabled == "true"}
				{if $smarty.session.db_is_logged_in == null}
					<li><a href="login.php">{$LANG.login}</a></li>
				{else}
					<li><a href="login.php?action=logout">{$LANG.logout}</a></li>
				{/if}
			{/if}
		</ul>
</div>
