<?php

include('./include/include_main.php'); 
#include('./config/config.php'); 
include("./lang/$language.inc.php");
include('./include/sql_patches.php');
#include('./include/menu.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}


$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

#Largest debtor query - start
if ($mysql > 4) {
	$sql = "
	SELECT	
	        {$tb_prefix}customers.c_id as ID,
	        {$tb_prefix}customers.c_name as Customer,
	        (select sum(inv_it_total) from {$tb_prefix}invoice_items,{$tb_prefix}invoices where  {$tb_prefix}invoice_items.inv_it_invoice_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = ID) as Total,
	        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where {$tb_prefix}account_payments.ac_inv_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = ID) as Paid,
	        (select (Total - Paid)) as Owing
	FROM
	        {$tb_prefix}customers,{$tb_prefix}invoices,{$tb_prefix}invoice_items
	WHERE
	        {$tb_prefix}invoice_items.inv_it_invoice_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = c_id
	GROUP BY
	        Owing DESC
	LIMIT 1;
	";

	$result = mysql_query($sql, $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result)) {
       		$largest_debtor = $Array['Customer'];
	};
}
#Largest debtor query - end

#Top customer query - start

if ($mysql > 4) {
	$sql2 = "
	SELECT
		{$tb_prefix}customers.c_id as ID,
	        {$tb_prefix}customers.c_name as Customer,
       		(select sum(inv_it_total) from {$tb_prefix}invoice_items,{$tb_prefix}invoices where  {$tb_prefix}invoice_items.inv_it_invoice_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = ID) as Total,
	        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where {$tb_prefix}account_payments.ac_inv_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = ID) as Paid,
	        (select (Total - Paid)) as Owing

	FROM
       		{$tb_prefix}customers,{$tb_prefix}invoices,{$tb_prefix}invoice_items
	WHERE
	        {$tb_prefix}invoice_items.inv_it_invoice_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = c_id
	GROUP BY
	        Total DESC
	LIMIT 1;
";

	$result2 = mysql_query($sql2, $conn) or die(mysql_error());

	while ($Array2 = mysql_fetch_array($result2)) {
        	$top_customer = $Array2['Customer'];
	};
}
#Top customer query - end

#Top biller query - start
if ($mysql > 4) {
	
	$sql3 = "
	SELECT
		{$tb_prefix}biller.b_name,  
		sum({$tb_prefix}invoice_items.inv_it_total) as Total 
	FROM 
		{$tb_prefix}biller, {$tb_prefix}invoice_items, {$tb_prefix}invoices 
	WHERE 
		{$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.b_id and {$tb_prefix}invoices.inv_id = {$tb_prefix}invoice_items.inv_it_invoice_id GROUP BY b_name ORDER BY Total DESC LIMIT 1;
	";

	$result3 = mysql_query($sql3, $conn) or die(mysql_error());

	while ($Array3 = mysql_fetch_array($result3)) {
	        $top_biller = $Array3['b_name'];
	};
}
#Top biller query - start


#Max patches applied - start
$sql4 = "
        SELECT
                count(sql_patch_ref) as count
        FROM 
                {$tb_prefix}sql_patchmanager
        ";

        $result4 = mysql_query($sql4, $conn) or die(mysql_error());

        while ($Array4 = mysql_fetch_array($result4)) {
                $max_patches_applied = $Array4['count'];
        };
#Top biller query - start


$display_block_notice .="<div>";

$display_block_notice .="<b align=center>$title</b><hr></hr>";

if ($mysql < 5) {
	$display_block_notice .=" 
		NOTE <a href='./documentation/info_pages/mysql4.html?keepThis=true&TB_iframe=true&height=300&width=500' title='Info :: MySQL 4' class='thickbox'><img src='./images/common/help-small.png'></img></a> : As you are using Mysql 4 some features have been disabled<br>
	";
};

if ($patch_count > $max_patches_applied) {
        $display_block_notice .=" 
                NOTE <a href='./documentation/info_pages/database_patches.html?keepThis=true&TB_iframe=true&height=300&width=500' title='Info :: Database patches to be applied' class='thickbox'><img src='./images/common/help-small.png'></img></a> :   There are database patches that need to be applied, please select <a href=\"./index.php?module=options&view=database_sqlpatches \">'Database Upgrade Manager'</a> from the Options menu and follow the instructions<br>
        ";
};

/*
$display_block_notice .="
<script type=\"text/javascript\">
if( $.browser.msie() ) { // defaults to undefined
	document.write(\"
		NOTE: As you are using MS Internet Explorer, some features of Simple Invoices have been disabled, please use <a href='http://www.getfirefox.com'>Firefox</a> to enable all features\")
	// Do something... ;
}
</script>
";

$display_block_notice .="
<script type=\"text/javascript\">
if( $.browser.konqueror() ) { // defaults to undefined
        document.write(\"
		NOTE: As you are using Konqueror, some features of Simple Invoices have been disabled, please use <a href='http://www.getfirefox.com'>Firefox</a> to enable all features\")
}
</script>
";

$display_block_notice .="
<script type=\"text/javascript\">
if( $.browser.safari() ) { // defaults to undefined
        document.write(\"
		NOTE: As you are using Safari, some features of Simple Invoices may not work as expected, please use <a href='http://www.getfirefox.com'>Firefox</a> to enable all features\")
}
</script>
";

*/

$display_block_notice .="";


$display_block ="
                <div id=\"list1\">
                <h2><img src=\"./images/common/reports.png\"></img>$LANG_stats</h2>
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

                <h2><img src=\"./images/common/menu.png\">$LANG_shortcut</h2>

                        <div id=\"item21\">
                                <div class=\"mytitle\">$LANG_getting_started</div>
                                <div class=\"mycontent\">
                                      <table>
                                        <tr>
                                                <td width=10%>
                                        		<a href=\"index.php?module=documentation/inline_docs&view=inline_instructions#faqs-what\">
								<img src=\"images/common/question.png\"></img>
								 $LANG_faqs_what
							</a>
                                		</td>		
						<td width=10%>
		                                        <a href=\"index.php?module=documentation/inline_docs&view=inline_instructions#faqs-need\">
	                                                	<img src=\"images/common/question.png\"></img>
								$LANG_faqs_need
							</a>
                                		</td>		
					</tr>
					<tr>
						<td width=10%>
		                                        <a href=\"index.php?module=documentation/inline_docs&view=inline_instructions#faqs-how\">
	                                                	<img src=\"images/common/question.png\"></img>
								$LANG_faqs_how
							</a>
                                		</td>		
						<td width=10%>
                		                        <a href=\"index.php?module=documentation/inline_docs&view=inline_instructions#faqs-types\">
	                                                	<img src=\"images/common/question.png\"></img>
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
				                        <a href=\"index.php?module=invoices&view=itemised\">
								<img src=\"images/common/itemised.png\"></img>
								$LANG_itemised_style
							</a>
                                		</td>		
						<td width=10%>
				        		<a href=\"index.php?module=invoices&view=total\">
								<img src=\"images/common/total.png\"></img>
								$LANG_total_style
							</a>
						</td>
						<td width=10%>
		                                        <a href=\"index.php?module=invoices&view=consulting\">
								<img src=\"images/common/consulting.png\"></img>
								$LANG_consulting_style
							</a>
                				</td>
					</tr>
					<tr>
						<td colspan=3 align=center class=\"align_center\">
                		                        <a href=\"index.php?module=documentation/inline_docs&view=inline_instructions#faqs-types\">
	                                                	<img src=\"images/common/question.png\"></img>
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
						<td width=10% align=center class=\"align_center\">
                                        		<a href=\"index.php?module=invoices&view=manage\">
								<img src=\"images/common/manage.png\"></img>
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
		                                        <a href=\"index.php?module=customers&view=add\">
                                                        	<img src=\"images/common/add.png\"></img>
								$LANG_insert_customer
							</a>
                                                </td>
                                                <td width=10%>
		                                        <a href=\"index.php?module=billers&view=add\">
                                                        	<img src=\"images/common/add.png\"></img>
								$LANG_insert_biller
							</a>
						</td>
                                                <td width=10%>
                                		        <a href=\"index.php?module=products&view=add\">
                                                        	<img src=\"images/common/add.png\"></img>
								$LANG_insert_product
							</a>
						</td>
					</tr>
					<tr>
                                                <td width=10%>
                		                        <a href=\"index.php?module=customers&view=manage\">
                                                        	<img src=\"images/common/customers.png\"></img>
								$LANG_manage_customers
							</a>
						</td>
                                                <td width=10%>
                                        		<a href=\"index.php?module=billers&view=manage\">
                                                        	<img src=\"images/common/biller.png\"></img>
								$LANG_manage_billers
							</a>
						</td>
                                                <td width=10%>
		                                        <a href=\"index.php?module=products&view=manage\">
                                                        	<img src=\"images/common/products.png\"></img>
								$LANG_manage_products
							</a>
						</td>
					</tr>
					</table>
                                </div>
                        </div>
                        <div id=\"item25\">
                                <div class=\"mytitle\">$LANG_options</div>
                                <div class=\"mycontent\">
                                      <table>
                                        <tr>
                                                <td width=10%>
		                                        <a href=\"index.php?module=system_defaults&view=manage\">
                                                        	<img src=\"images/common/defaults.png\"></img>
								$LANG_system_defaults
							</a>
						</td>
                                                <td width=10%>
                		                        <a href=\"index.php?module=tax_rates&view=manage\">
                                                        	<img src=\"images/common/tax.png\"></img>
								$LANG_tax_rates
							</a>
						</td>
                                                <td width=10%>
		                                        <a href=\"index.php?module=preferences&view=manage\">
                                                        	<img src=\"images/common/preferences.png\"></img>
								$LANG_invoice_preferences
							</a>
						</td>
						</tr>
						<tr>
                                                <td width=10%>
                                		        <a href=\"index.php?module=payment_types&view=manage\">
                                                        	<img src=\"images/common/payment.png\"></img>
								$LANG_payment_types
							</a>
						</td>
                                                <td width=10%>
                		                        <a href=\"database_sqlpatches.php\">
                                                        	<img src=\"images/common/upgrade.png\"></img>
								$LANG_database_upgrade_manager
							</a>
						</td>
                                                <td width=10%>
		                                        <a href=\"backup_database.php\">
                                                        	<img src=\"images/common/backup.png\"></img>
								$LANG_backup_database
							</a>
						</td>
					</tr>
					</table>
                                </div>
                        </div>
                        <div id=\"item26\">
                                <div class=\"mytitle\">$LANG_help</div>
                                <div class=\"mycontent\">
                                      <table>
                                        <tr>
                                                <td width=10%>
                                        		<a href=\"index.php?module=documentation/inline_docs&view=inline_instructions#installation\">
                                                        	<img src=\"images/common/help.png\"></img>
								$LANG_installation
							</a>
						</td>	
						<td width=10%>
                		                        <a href=\"index.php?module=documentation/inline_docs&view=inline_instructions#upgrading\">
                                                        	<img src=\"images/common/help.png\"></img>
								$LANG_upgrading_simple_invoices
							</a>
						</td>	
					</tr>
					<tr>
						<td width=10% class=\"align_center\" colspan=\"2\">
		                                        <a href=\"index.php?module=documentation/inline_docs&view=inline_instructions#prepare\">
                                                        	<img src=\"images/common/help.png\"></img>
								$LANG_prepare_simple_invoices
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

<script type="text/javascript" src="./include/jquery.js"></script>
<script type="text/javascript" src="./include/jquery.thickbox.js"></script>
<script type="text/javascript" src="./include/jquery.accordian.js"></script>
<!--<script type="text/javascript" src="./include/jquery.jqbrowser.js"></script>-->
<link rel="stylesheet" type="text/css" href="./src/include/css/jquery.thickbox.css" media="screen"/>

</head>
<BODY>
<!-- <table align="center" >-->

<?php echo $display_block_notice; ?>
<?php 
	echo $display_block;

?>
</div>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<br>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
