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
	                                                <img src=\"images/question.png\"></img>
                                        		<a href=\"./inline_instructions.php#faqs-what\">$LANG_faqs_what</a><br/>
                                		</td>		
						<td width=10%>
	                                                <img src=\"images/question.png\"></img>
		                                        <a href=\"./inline_instructions.php#faqs-need\">$LANG_faqs_need</a><br/>
                                		</td>		
					</tr>
					<tr>
						<td width=10%>
	                                                <img src=\"images/question.png\"></img>
		                                        <a href=\"inline_instructions.php#faqs-how\">$LANG_faqs_how</a><br/>
                                		</td>		
						<td width=10%>
	                                                <img src=\"images/question.png\"></img>
                		                        <a href=\"inline_instructions.php#faqs-types\">$LANG_faqs_type</a>
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
							<img src=\"images/itemised.png\"></img>
				                         <a href=\"invoice_itemised.php\">$LANG_itemised</a><br>soem stuff<bt>more stuff
                                		</td>		
						<td width=10%>
							<img src=\"images/total.png\"></img>
				        		<a href=\"invoice_total.php\">$LANG_total</a>
						</td>
						<td width=10%>
							<img src=\"images/consulting.png\"></img>
		                                        <a href=\"invoice_consulting.php\">$LANG_consulting</a>
                				</td>
					<tr>
					</table>
		                </div>
                        </div>
                        <div id=\"item23\">
                                <div class=\"mytitle\">$LANG_manage_existing_invoice</div>
                                <div class=\"mycontent\">
					<table>
					<tr>
						<td width=10% align=center>
							<img src=\"images/manage.png\"></img>
                                        <a href=\"manage_invoices.php\">$LANG_manage_invoices</a>
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
                                                        <img src=\"images/add.png\"></img>
		                                        <a href=\"insert_customer.php\">$indx_insert_customer</a><br/>
                                                </td>
                                                <td width=10%>
                                                        <img src=\"images/add.png\"></img>
		                                        <a href=\"insert_biller.php\">$indx_insert_biller</a>
						</td>
                                                <td width=10%>
                                                        <img src=\"images/add.png\"></img>
                                		        <a href=\"insert_product.php\">$indx_insert_product</a><br/>
						</td>
					</tr>
					<tr>
                                                <td width=10%>
                                                        <img src=\"images/customers.png\"></img>
                		                        <a href=\"manage_customers.php\">Manage Customers</a><br/>
						</td>
                                                <td width=10%>
                                                        <img src=\"images/biller.png\"></img>
                                        		<a href=\"manage_biller.php\">Manage Billers</a><br/>
						</td>
                                                <td width=10%>
                                                        <img src=\"images/products.png\"></img>
		                                        <a href=\"manage_products.php\">Manage Products</a><br/>
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
                                                        <img src=\"images/defaults.png\"></img>
		                                        <a href=\"manage_system_defaults.php\">$indx_options_sys_defaults</a>
						</td>
                                                <td width=10%>
                                                        <img src=\"images/tax.png\"></img>
                		                        <a href=\"manage_tax_rates.php\">$indx_options_tax_rates</a><br/>
						</td>
                                                <td width=10%>
                                                        <img src=\"images/preferences.png\"></img>
						
		                                        <a href=\"manage_preferences.php\">$indx_options_inv_pref</a><br/>
						</td>
						</tr>
						<tr>
                                                <td width=10%>
                                                        <img src=\"images/payment.png\"></img>
                                		        <a href=\"manage_payment_types.php\">$indx_options_payment_types</a><br/>
						</td>
                                                <td width=10%>
                                                        <img src=\"images/upgrade.png\"></img>
                		                        <a href=\"database_sqlpatches.php\">$indx_options_upgrade</a> <br/>
						</td>
                                                <td width=10%>
                                                        <img src=\"images/backup.png\"></img>
		                                        <a href=\"backup_database.php\">$indx_options_backup</a>
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
                                                        <img src=\"images/help.png\"></img>
                                        		<a href=\"inline_instructions.php#installation\">$indx_help_install<br/></a>
						</td>	
						<td width=10%>
                                                        <img src=\"images/help.png\"></img>
                		                        <a href=\"inline_instructions.php#upgrading\">$indx_help_upgrade<br/></a>
						</td>	
					</tr>
					<tr>
						<td width=10%>
                                                        <img src=\"images/help.png\"></img>
		                                        <a href=\"inline_instructions.php#prepare\">$indx_help_prepare<br/></a>
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
                <script type="text/javascript" src="./include/jquery-accordian.js"></script>
                <style type="text/css">

			/*The CSS code for the fronta page of Simple Invoices - start*/
			body{background:#F5F5F5 url('./themes/<?php echo $theme; ?>/images/gb_top.gif') repeat-x; color: #222; margin: 0;      padding: 0;}

                        #list1 { width:48%;  position:absolute; top:15%; right:1em; }
                        .title { cursor:pointer; border:1px solid #CCCCCC; margin-top:0.5em; padding:0.1em; }
                        .on1  .title { background-color:#E4EFC7; }
                        .off1 .title { background-color:#E0E0E0; }
                        /*.content    { background-color:#F5F5F5; padding:0.1em; border:1px solid #C0C0C0; border-top-width:0; }*/
                        .content    { background-color:#FFF; padding:0.1em; border:1px solid #C0C0C0; border-top-width:0; }

                        #list2 { width:48%; position:absolute; top:15%; left:1em; }
                        .mytitle { cursor:pointer; border:1px solid #CCCCCC; margin-top:0.5em; padding:0.1em; }
                        .on  .mytitle { background-color:#E4EFC7; }
                        .off .mytitle { background-color:#E0E0E0; }
                        /*.mycontent    { background-color:#F5F5F5; padding:0.1em; border:1px solid #C0C0C0; border-top-width:0; }*/
                        .mycontent    { background-color:#FFF; padding:0.1em; border:1px solid #C0C0C0; border-top-width:0; }

			a:link {  text-decoration: none; }
			a:visited { text-decoration: none; }
			a:active { text-decoration: none; }
			a:hover {text-decoration: underline; color:  #ff0000; }

			/*The CSS code for the fronta page of Simple Invoices - end*/
                </style>
<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">

</head>
<BODY>

<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>


<br>

<div id="container">
<div id=header>
<!--                <h1 align=center><?php echo $LANG_welcome; echo $title; ?></h1>-->
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php echo $display_block; ?>
<div id="footer"></div>

                </div>

</div>

</BODY>
</HTML>
