<?php
include('./include/menu.php');
include('./config/config.php'); 
include("./lang/$language.inc.php");

$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );


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




?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

                <title><?php echo $title; ?></title>

                <script type="text/javascript" src="./include/jquery.js"></script>
                <script type="text/javascript" src="./include/jquery-accordian.js"></script>
                <style type="text/css">

			/*The CSS code for the mina body of Simple Invoices - start*/
			body{background:#F5F5F5 url('./themes/<?php echo $theme; ?>/images/gb_top.gif') repeat-x; color: #222; margin: 0;      padding: 0;}

                        #list1 { width:48%;  position:absolute; top:15%; right:1em; }
                        .title { cursor:pointer; border:1px solid #CCCCCC; margin-top:0.5em; padding:0.1em; }
                        .on1  .title { background-color:#E4EFC7; }
                        .off1 .title { background-color:#E0E0E0; }
                        .content    { background-color:#F5F5F5; padding:0.1em; border:1px solid #C0C0C0; border-top-width:0; }

                        #list2 { width:48%; position:absolute; top:15%; left:1em; }
                        .mytitle { cursor:pointer; border:1px solid #CCCCCC; margin-top:0.5em; padding:0.1em; }
                        .on  .mytitle { background-color:#E4EFC7; }
                        .off .mytitle { background-color:#E0E0E0; }
                        .mycontent    { background-color:#F5F5F5; padding:0.1em; border:1px solid #C0C0C0; border-top-width:0; }

			a:link {  text-decoration: none; }
			a:visited { text-decoration: none; }
			a:active { text-decoration: none; }
			a:hover {text-decoration: underline; color:  #ff0000; }

                </style>

</head>
<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>
<!-- <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css"> -->
<br>


                <h1 align=center><?php echo $indx_welcome; echo $title; ?></h1>
                <div id="list1">
                <h2><img src="./images/reports.png"></img><?php echo $indx_stats; ?></h2>
                        <div id="item11">

                                <div class="title"><?php echo $indx_stats_debtor; ?></div>

                                <div class="content">
			
				<?php echo $largest_debtor; ?>
                                </div>
                        </div>

                        <div id="item12">

                                <div class="title"><?php echo $indx_stats_customer; ?></div>

                                <div class="content">

				<?php echo $top_customer; ?>

                                </div>

                        </div>

                        <div id="item13">

                                <div class="title"><?php echo $indx_stats_biller; ?></div>

                                <div class="content">

				<?php echo $top_biller; ?>

                                </div>

                        </div>
                </div>


               <div id="list2">

                <h2><img src="./images/menu.png"> <?php echo $indx_shortcut; ?></h2>

                        <div id="item21">
                                <div class="mytitle"><?php echo $indx_getting_started; ?></div>
                                <div class="mycontent">
                                        <a href="./inline_instructions.php#faqs-what"><?php echo $indx_faqs_what; ?></a><br/>
                                        <a href="./inline_instructions.php#faqs-need"><?php echo $indx_faqs_need; ?></a><br/>
                                        <a href="inline_instructions.php#faqs-how"><?php echo $indx_faqs_how; ?></a><br/>
                                        <a href="inline_instructions.php#faqs-types"><?php echo $indx_faqs_type; ?></a>
                                </div>
                        </div>

                        <div id="item22">
                                <div class="mytitle"><?php echo $indx_create_invoice; ?></div>
                                <div class="mycontent">
                                        <a href="invoice_itemised.php"><?php echo $indx_invoice_itemised; ?></a><br/>
                                        <a href="invoice_total.php"><?php echo $indx_invoice_total; ?></a><br/>
                                        <a href="invoice_consulting.php"><?php echo $indx_invoice_consulting; ?></a><br/>
                                </div>
                        </div>
                        <div id="item23">
                                <div class="mytitle"><?php echo $indx_manage_existing_invoice; ?></div>
                                <div class="mycontent">
                                        <a href="manage_invoices.php"><?php echo $indx_manage_invoices; ?></a><br/>
                                </div>
                        </div>

                        <div id="item24">
                                <div class="mytitle"><?php echo $indx_manage_data; ?></div>
                                <div class="mycontent">
                                        <a href="insert_biller.php"><?php echo $indx_insert_biller; ?></a><br/>
                                        <a href="insert_customer.php"><?php echo $indx_insert_customer; ?></a><br/>
                                        <a href="insert_product.php"><?php echo $indx_insert_product; ?></a><br/>
                                </div>
                        </div>
                        <div id="item25">
                                <div class="mytitle"><?php echo $indx_options; ?></div>
                                <div class="mycontent">
                                        <a href="manage_system_defaults.php"><?php echo $indx_options_sys_defaults; ?></a><br/>
                                        <a href="manage_tax_rates.php"><?php echo $indx_options_tax_rates; ?></a><br/>
                                        <a href="manage_preferences.php"><?php echo $indx_options_inv_pref; ?></a><br/>
                                        <a href="manage_payment_types.php"><?php echo $indx_options_payment_types; ?></a><br/>
                                        <a href="database_sqlpatches.php"><?php echo $indx_options_upgrade; ?></a> <br/>
                                        <a href="backup_database.php"><?php echo $indx_options_backup; ?></a>
                                </div>
                        </div>
                        <div id="item26">
                                <div class="mytitle"><?php echo $indx_help; ?></div>
                                <div class="mycontent">
                                        <a href="inline_instructions.php#installation"><?php echo $indx_help_install; ?><br/></a>
                                        <a href="inline_instructions.php#upgrading"><?php echo $indx_help_upgrade; ?><br/></a>
                                        <a href="inline_instructions.php#prepare"><?php echo $indx_help_prepare; ?><br/></a>
                                </div>
                        </div>
                </div>
 




</BODY>
</HTML>







