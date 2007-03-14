<?php 
include_once('./include/include_main.php');
echo <<<EOD
<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<title>Simple Invoices</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>
	<link rel="stylesheet" type="text/css" href="include/jquery.autocomplete.css" title="default" media="screen" />
	<link rel="stylesheet" type="text/css" href="include/jquery.datePicker.css" title="default" media="screen" />
	<link rel="stylesheet" type="text/css" href="./src/include/css/header1.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./src/include/css/header2.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./src/include/css/screen.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./src/include/css/print.css" media="print"/>
	<link rel="stylesheet" type="text/css" href="./src/include/css/blue.css" media="screen"/>


<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery.dom_creator.js"></script>
<script type="text/javascript" src="include/jquery.datePicker.js"></script>
<script type="text/javascript" src="include/jquery.datePicker.conf.js"></script>
<script type='text/javascript' src='include/jquery.autocomplete.js'></script>
<script type='text/javascript' src='include/jquery.autocomplete.conf.js'></script>
<script src="./include/jquery.tabs.js" type="text/javascript"></script>

<script type="text/javascript" src="./src/include/js/ibox.js"></script>

<!--[if gte IE 5.5]>
<script language="JavaScript" src="dhtml.js" type="text/JavaScript"></script>
<link rel="stylesheet" type="text/css" href="./src/include/css/iehacks.css" media="all"/>
<script language="JavaScript" src="./src/include/js/dhtml.js" type="text/JavaScript"></script>
<link rel="stylesheet" type="text/css" href="./src/include/css/iehacks.css" media="all"/>
<![endif]-->



<!-- customer-details -->
<link rel="stylesheet" href="./include/css/tabs.css" type="text/css" media="print, projection, screen" />
	<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
	<!--[if lte IE 7]>
	<link rel="stylesheet" href="./include/css/tabs-ie.css" type="text/css" media="projection, screen" />
	<![endif]-->
	<style type="text/css" media="screen, projection">
	    /* just to make this demo look a bit better */
	    h4 {
		margin: 0;
		padding: 0;
	    }
	    ul {
		list-style: none;
		
	    }
	    body>ul>li {
		display: inline;
	    }
	    body>ul>li:before {
		content: ", ";
	    }
	    body>ul>li:first-child:before {
		content: "";
	    }
	</style>
	<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
	<!--[if lte IE 7]>
	<style type="text/css" media="screen, projection">
	    body {
		font-size: 100%; /* resizable fonts */
	    }
	</style>
	<![endif]-->

	<script type="text/javascript">//<![CDATA[
	    $(document).ready(function() {
		$('#container-1').tabs();
		$('#trigger-tab').after('<p><a href="#" onclick="$(\'#container-1\').triggerTab(3); return false;">Activate third tab</a></p>');
		$('#custom-tab-by-hash').title('New window').click(function() {
		    var win = window.open(this.href, '', 'directories,location,menubar,resizable,scrollbars,status,toolbar');
		    win.focus();
		});
	    });
	//]]></script>
<!-- customer-details -->



	<div id="Header">
		<div id="Tabs">
			<ul id="MainTabs">
				<ul id="navmenu">
					<li><a href="index.php">{$LANG_home}</a></li>
					<li><a href="index.php?module=invoices&view=manage">{$LANG_invoices} +</a>
						<ul>
							<li><a href="index.php?module=invoices&view=manage">{$LANG_manage_invoices}</a></li>
							<li></li>
							<li><a href="index.php?module=invoices&view=total">{$LANG_new_invoice_total}</a></li>
							<li><a href="index.php?module=invoices&view=itemised">{$LANG_new_invoice_itemised}</a></li>
							<li><a href="index.php?module=invoices&view=consulting">{$LANG_new_invoice_consulting}</a></li>
						</ul>
					</li>
					<li><a href="index.php?module=customers&view=manage">{$LANG_customers} +</a>
						<ul>
							<li><a href="index.php?module=customers&view=manage">{$LANG_manage_customers}</a></li>
							<li><a href="index.php?module=customers&view=add">{$LANG_add_customer}</a></li>
						</ul>
					</li>
					<li><a href="index.php?module=products&view=manage">{$LANG_products} +</a>
						<ul>
							<li><a href="index.php?module=products&view=manage">{$LANG_manage_products}</a></li>
							<li><a href="index.php?module=products&view=add">{$LANG_add_product}</a></li>
						</ul>
					</li>
					<li><a href="index.php?module=billers&view=manage">{$LANG_billers} +</a>
						<ul>
							<li><a href="index.php?module=billers&view=manage">{$LANG_manage_billers}</a></li>
							<li><a href="index.php?module=billers&view=add">{$LANG_add_biller}</a></li>
						</ul>
					</li>
					<li><a href="index.php?module=payments&view=manage">{$LANG_payments} +</a>
						<ul>
							<li><a href="index.php?module=payments&view=manage">{$LANG_manage_payments}</a></li>
							<li><a href="index.php?module=payments&view=process&op=pay_invoice">{$LANG_process_payment}</a></li>
						</ul>
					</li>
					<li><a href="index.php">{$LANG_reports} +</a>
						<ul>
							<li><a href="index.php?module=reports&view=report_sales_total">{$LANG_sales} +</a>
								<ul>
									<li><a href="index.php?module=reports&view=report_sales_total">{$LANG_total_sales}</a></li>
								</ul>
							</li>
							<li><a href="index.php?module=reports&view=report_sales_customers_total">{$LANG_sales_by_customers} +</a>
								<ul>
									<li><a href="./index.php?module=reports&view=report_sales_customers_total">{$LANG_total_sales_by_customer}</a>
									</li>
								</ul>
							</li>
							<li><a href="./index.php?module=reports&view=report_tax_total">{$LANG_tax} +</a>
								<ul>
									<li><a href="./index.php?module=reports&view=report_tax_total">{$LANG_total_taxes}</a></li>
								</ul>
							</li>
							<li><a href="index.php?module=reports&view=report_products_sold_total">{$LANG_product_sales} +</a>
								<ul>
									<li><a href="./index.php?module=reports&view=report_products_sold_total">{$LANG_products_sold_total}</a>
									</li>
								</ul>
							</li>
							<li><a href="./index.php?module=reports&view=report_products_sold_by_customer">{$LANG_products_by_customer} +</a>
								<ul>
									<li><a href="./index.php?module=reports&view=report_products_sold_by_customer">{$LANG_products_sold_customer_total}</a>
									</li>
								</ul>
							</li>
							<li><a href="index.php?module=reports&view=report_biller_total">{$LANG_biller_sales} +</a>
								<ul>
									<li><a href="index.php?module=reports&view=report_biller_total">{$LANG_biller_sales_total}</a></li>
									<li><a href="./index.php?module=reports&view=report_biller_by_customer">{$LANG_biller_sales_by_customer_totals}</a>
									</li>
								</ul>
							</li>
							<li><a href="./index.php?module=reports&view=report_debtors_by_amount">{$LANG_debtors} +</a>
								<ul>
									<li><a href="./index.php?module=reports&view=report_debtors_by_amount">{$LANG_debtors_by_amount_owed}</a>
									</li>
									<li><a href="./index.php?module=reports&view=report_debtors_by_aging">{$LANG_debtors_by_aging_periods}</a>
									</li>
									<li><a href="./index.php?module=reports&view=report_debtors_owing_by_customer">{$LANG_total_owed_per_customer}r</a>
									</li>
									<li><a href="./index.php?module=reports&view=report_debtors_aging_total">{$LANG_total_by_aging_periods}</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
					<li><a href="#">{$LANG_options} +</a>
						<ul>
							<li>
								<a href="./index.php?module=system_defaults&view=manage">{$LANG_system_defaults}</a>
							</li>
							<li>
								<a href="./index.php?module=custom_fields&view=manage">{$LANG_custom_fields_upper}</a>
							</li>
							<li></li>
							<li>
								<a href="./index.php?module=tax_rates&view=manage">{$LANG_tax_rates} +</a>
								<ul>
									<li>
										<a href="./index.php?module=tax_rates&view=manage">{$LANG_manage_tax_rates}</a>
									</li>
									<li>
										<a href="./index.php?module=tax_rates&view=add">{$LANG_add_tax_rate}</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="./index.php?module=preferences&view=manage">{$LANG_invoice_preferences} +</a>
								<ul>
									<li>
										<a href="./index.php?module=preferences&view=manage">{$LANG_manage_invoice_preferences}</a>
									</li>
									<li>
										<a href="./index.php?module=preferences&view=add">{$LANG_add_invoice_preference}</a>
									</li>
								</ul>
							</li>
							<li>
								<a href="./index.php?module=payment_types&view=manage">{$LANG_payment_types} +</a>
								<ul>
									<li>
										<a href="./index.php?module=payment_types&view=manage">{$LANG_manage_payment_types}</a>
									</li>
									<li>
										<a href="./index.php?module=payment_types&view=add">{$LANG_add_payment_type}</a>
									</li>
								</ul>
							</li>
							<li></li>
							<li>
								<a href="./index.php?module=options&view=database_sqlpatches">{$LANG_database_upgrade_manager}</a>
							</li>
							<li>
								<a href="./index.php?module=options&view=backup_database">{$LANG_backup_database}</a>
							</li>
							<li>
								<a href="./index.php?module=options&view=sanity_check">{$LANG_sanity_check}</a>
							</li>
							<li></li>
							<li>
								<a href="index.php?module=documentation/inline_docs&view=inline_instructions">{$LANG_help} +</a>
								<ul>
									<li>
										<a href="index.php?module=documentation/inline_docs&view=inline_instructions#installation">{$LANG_installation}</a>
									</li>
									<li>
										<a href="index.php?module=documentation/inline_docs&view=inline_instructions#upgrading">{$LANG_upgrading_simple_invoices}</a>
									</li>
									<li><a href="index.php?module=documentation/inline_docs&view=inline_instructions#prepare">{$LANG_prepare_simple_invoices}</a>
									</li>
									<li><a href="index.php?module=documentation/inline_docs&view=inline_instructions#use">{$LANG_using_simple_invoices}</a>
									</li>
									<li><a href="index.php?module=documentation/inline_docs&view=inline_instructions#faqs">{$LANG_faqs}</a></li>
									<li><a href="index.php?module=options&view=help">{$LANG_get_help}</a></li>
								</ul>
							</li>
							<li><a href="index.php?module=documentation/inline_docs&view=about">{$LANG_about} +</a>
								<ul>
									<li><a href="index.php?module=documentation/inline_docs&view=about">{$LANG_about}</a></li>
									<li><a href="index.php?module=documentation/inline_docs&view=inline_changelog">{$LANG_change_log}</a></li>
									<li><a href="index.php?module=documentation/inline_docs&view=inline_credits">{$LANG_credits}</a></li>
									<li><a href="index.php?module=documentation/inline_docs&view=inline_license">{$LANG_license}</a></li>
								</ul>
							</li>
						</ul>
					</li>
EOD;

/*Show login tab id session var not set */
if (!isset($_SESSION['db_is_logged_in'])) {
	echo <<<EOD
		   <li><a href="login.php">{$LANG_login}</a></li>
EOD;
}

/*Show login tab id session var  set */
if (isset($_SESSION['db_is_logged_in'])) {
echo <<<EOD
   <li><a href="logout.php">{$LANG_logout}</a></li>
EOD;
}

echo <<<EOD
 </ul>
</div id="Tabs">
</div id="Header">

  <div id="Wrapper">
         <div id="Container">
 
 <div class="Full">
   <div class="col">
EOD;
