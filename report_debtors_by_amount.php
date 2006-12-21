<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include("./include/include_main.php"); ?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

<!-- thickbox js and css stuff -->
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/jquery.thickbox.js"></script>

    <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/jquery.thickbox.css" media="all"/>

<title><?php echo $title; echo $mi_page_title; ?></title>
</head>
<body>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="./themes/<?php echo $theme; ?>/tables.css">
<br>
<div id="container">
<div id=header></div id=header>

<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./reports/PHPReportMaker.php";

   $sSQL = "

SELECT
        inv_id,
        (select b_name from si_biller where b_id = si_invoices.inv_biller_id) as Biller,
        (select c_name from si_customers where c_id = si_invoices.inv_customer_id) as Customer,
        (select sum(inv_it_total) from si_invoice_items WHERE inv_it_invoice_id = inv_id) as Total,
        ( select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) from si_account_payments where  ac_inv_id = inv_id ) as Paid,
        (select (Total - Paid)) as Owing ,
        inv_date
FROM
        si_invoices,si_account_payments,si_invoice_items, si_biller, si_customers
WHERE
        inv_it_invoice_id = si_invoices.inv_id
GROUP BY
        inv_id
ORDER BY
        Owing DESC;

";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("reports/report_debtors_by_amount.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_host");
   $oRpt->setDatabaseInterface("mysql");
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("$db_name");
   $oRpt->run();
?>

<div id="footer"></div>
</div>
</div>
</div>
<a href="./documentation/text/reports_xsl.html?keepThis=true&TB_iframe=true&height=300&width=650" title="Info :: Reports" class="thickbox"><font color="red">Got "OOOOPS, THERE'S AN ERROR HERE." error?</font></a>
</body>
</html>		
