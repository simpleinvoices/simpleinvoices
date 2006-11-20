<?php

include('./include/include_main.php'); 
#include('./config/config.php'); 
include("./lang/$language.inc.php");
#include('./include/menu.php');

$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

#Largest debtor query - start
$sql = "
SELECT
        si_customers.c_id as ID,
        si_customers.c_name as Customer,
        (select sum(inv_it_total) from si_invoice_items,si_invoices where  si_invoice_items.inv_it_invoice_id = si_invoices.inv_id and si_invoices.inv_customer_id = ID) as Total,
        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from si_account_payments,si_invoices where si_account_payments.ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = ID) as Paid,
        (select (Total - Paid)) as Owing

FROM
        si_customers,si_invoices,si_invoice_items
WHERE
        si_invoice_items.inv_it_invoice_id = si_invoices.inv_id and si_invoices.inv_customer_id = c_id
GROUP BY
        Owing DESC
LIMIT 1;

";

$result = mysql_query($sql, $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result)) {
        $largest_debtor = $Array['Customer'];
};
#Largest debtor query - end

#Top customer query - start

$sql2 = "
SELECT
        si_customers.c_id as ID,
        si_customers.c_name as Customer,
        (select sum(inv_it_total) from si_invoice_items,si_invoices where  si_invoice_items.inv_it_invoice_id = si_invoices.inv_id and si_invoices.inv_customer_id = ID) as Total,
        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from si_account_payments,si_invoices where si_account_payments.ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = ID) as Paid,
        (select (Total - Paid)) as Owing

FROM
        si_customers,si_invoices,si_invoice_items
WHERE
        si_invoice_items.inv_it_invoice_id = si_invoices.inv_id and si_invoices.inv_customer_id = c_id
GROUP BY
        Total DESC
LIMIT 1;

";

$result2 = mysql_query($sql2, $conn) or die(mysql_error());

while ($Array2 = mysql_fetch_array($result2)) {
        $top_customer = $Array2['Customer'];
};
#Top customer query - end

#Top biller query - start

$sql3 = "
SELECT
	si_biller.b_name,  
	sum(si_invoice_items.inv_it_total) as Total 
FROM 
	si_biller, si_invoice_items, si_invoices 
WHERE 
	si_invoices.inv_biller_id = si_biller.b_id and si_invoices.inv_id = si_invoice_items.inv_it_invoice_id GROUP BY b_name ORDER BY Total DESC LIMIT 1;

";

$result3 = mysql_query($sql3, $conn) or die(mysql_error());

while ($Array3 = mysql_fetch_array($result3)) {
        $top_biller = $Array3['b_name'];
};
#Top biller query - start

$display_block ="

                <div id=\"list1\">
                <h2><img src=\"./images/reports.png\"></img>$LANG_stats</h2>
                        <div id=\"item11\">

                                <div class=\"title\">$LANG_stats_debtor</div>

                                <div class=\"content\">
			
				$largest_debtor
                                </div>
                        </div>

                        <div id=\"item12\">

                                <div class=\"title\">$LANG_stats_customer</div>

                                <div class=\"content\">

				$top_customer

                                </div>

                        </div>

                        <div id=\"item13\">

                                <div class=\"title\">$LANG_stats_biller</div>

                                <div class=\"content\">

				$top_biller

                                </div>

                        </div>
                </div>


               <div id=\"list2\">

                <h2><img src=\"./images/menu.png\">$LANG_shortcut</h2>

                        <div id=\"item21\">
                                <div class=\"mytitle\">$LANG_getting_started</div>
                                <div class=\"mycontent\">
                                      <table>
                                        <tr>
                                                <td width=10%>
                                        		<a href=\"./inline_instructions.php#faqs-what\">
								<img src=\"images/question.png\"></img>
								 $LANG_faqs_what
							</a>
                                		</td>		
						<td width=10%>
		                                        <a href=\"./inline_instructions.php#faqs-need\">
	                                                	<img src=\"images/question.png\"></img>
								$LANG_faqs_need
							</a>
                                		</td>		
					</tr>
					<tr>
						<td width=10%>
		                                        <a href=\"inline_instructions.php#faqs-how\">
	                                                	<img src=\"images/question.png\"></img>
								$LANG_faqs_how
							</a>
                                		</td>		
						<td width=10%>
                		                        <a href=\"inline_instructions.php#faqs-types\">
	                                                	<img src=\"images/question.png\"></img>
								$LANG_faqs_type
							</a>
                                		</td>		
					</tr>
					</table>
                                </div>
                        </div>

                        <div id=\"item22\">
                                <div class=\"mytitle\">$LANG_create_invoice</div>
                                <div class=\"mycontent\">
					<table>
					<tr>
						<td width=10%>
				                        <a href=\"invoice_itemised.php\">
								<img src=\"images/itemised.png\"></img>
								$LANG_itemised_style
							</a>
                                		</td>		
						<td width=10%>
				        		<a href=\"invoice_total.php\">
								<img src=\"images/total.png\"></img>
								$LANG_total_style
							</a>
						</td>
						<td width=10%>
		                                        <a href=\"invoice_consulting.php\">
								<img src=\"images/consulting.png\"></img>
								$LANG_consulting_style
							</a>
                				</td>
					</tr>
					<tr>
						<td colspan=3 align=center>
                		                        <a href=\"inline_instructions.php#faqs-types\">
	                                                	<img src=\"images/question.png\"></img>
								$LANG_faqs_type
							</a>
                                		</td>		
					</tr>
					</table>
		                </div>
                        </div>
                        <div id=\"item23\">
                                <div class=\"mytitle\">$LANG_manage_existing_invoice</div>
                                <div class=\"mycontent\">
					<table>
					<tr>
						<td width=10% align=center>
                                        		<a href=\"manage_invoices.php\">
								<img src=\"images/manage.png\"></img>
								$LANG_manage_invoices
							</a>
						</td>
					</tr>
					</table>
                                </div>
                        </div>

                        <div id=\"item24\">
                                <div class=\"mytitle\">$LANG_manage_data</div>
	                        <div class=\"mycontent\">
	                                <table>
                                        <tr>
                                                <td width=10%>
		                                        <a href=\"insert_customer.php\">
                                                        	<img src=\"images/add.png\"></img>
								$indx_insert_customer
							</a>
                                                </td>
                                                <td width=10%>
		                                        <a href=\"insert_biller.php\">
                                                        	<img src=\"images/add.png\"></img>
								$indx_insert_biller
							</a>
						</td>
                                                <td width=10%>
                                		        <a href=\"insert_product.php\">
                                                        	<img src=\"images/add.png\"></img>
								$indx_insert_product
							</a>
						</td>
					</tr>
					<tr>
                                                <td width=10%>
                		                        <a href=\"manage_customers.php\">
                                                        	<img src=\"images/customers.png\"></img>
								Manage Customers
							</a>
						</td>
                                                <td width=10%>
                                        		<a href=\"manage_billers.php\">
                                                        	<img src=\"images/biller.png\"></img>
								Manage Billers
							</a>
						</td>
                                                <td width=10%>
		                                        <a href=\"manage_products.php\">
                                                        	<img src=\"images/products.png\"></img>
								Manage Products
							</a>
						</td>
					</tr>
					</table>
                                </div>
                        </div>
                        <div id=\"item25\">
                                <div class=\"mytitle\">$indx_options</div>
                                <div class=\"mycontent\">
                                      <table>
                                        <tr>
                                                <td width=10%>
		                                        <a href=\"system_default_details.php\">
                                                        	<img src=\"images/defaults.png\"></img>
								$indx_options_sys_defaults
							</a>
						</td>
                                                <td width=10%>
                		                        <a href=\"manage_tax_rates.php\">
                                                        	<img src=\"images/tax.png\"></img>
								$indx_options_tax_rates
							</a>
						</td>
                                                <td width=10%>
		                                        <a href=\"manage_preferences.php\">
                                                        	<img src=\"images/preferences.png\"></img>
								$indx_options_inv_pref
							</a>
						</td>
						</tr>
						<tr>
                                                <td width=10%>
                                		        <a href=\"manage_payment_types.php\">
                                                        	<img src=\"images/payment.png\"></img>
								$indx_options_payment_types
							</a>
						</td>
                                                <td width=10%>
                		                        <a href=\"database_sqlpatches.php\">
                                                        	<img src=\"images/upgrade.png\"></img>
								$indx_options_upgrade
							</a>
						</td>
                                                <td width=10%>
		                                        <a href=\"backup_database.php\">
                                                        	<img src=\"images/backup.png\"></img>
								$indx_options_backup
							</a>
						</td>
					</tr>
					</table>
                                </div>
                        </div>
                        <div id=\"item26\">
                                <div class=\"mytitle\">$indx_help</div>
                                <div class=\"mycontent\">
                                      <table>
                                        <tr>
                                                <td width=10%>
                                        		<a href=\"inline_instructions.php#installation\">
                                                        	<img src=\"images/help.png\"></img>
								$indx_help_install
							</a>
						</td>	
						<td width=10%>
                		                        <a href=\"inline_instructions.php#upgrading\">
                                                        	<img src=\"images/help.png\"></img>
								$indx_help_upgrade
							</a>
						</td>	
					</tr>
					<tr>
						<td width=10%>
		                                        <a href=\"inline_instructions.php#prepare\">
                                                        	<img src=\"images/help.png\"></img>
								$indx_help_prepare
							</a>
						</td>	
					</tr>
					</table>
                                </div>
                        </div>
                        </div>

";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

                <title><?php echo $title; ?></title>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

                <script type="text/javascript" src="./include/jquery.js"></script>
                <script type="text/javascript" src="./include/jquery.accordian.js"></script>

		<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/index.css">

</head>
<BODY>

<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>


<br>

<div id="container">
<div id=header>
                <b align=center><?php echo $title; ?></b>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<?php echo $display_block; ?>

                </div>

</div>

</BODY>
</HTML>
