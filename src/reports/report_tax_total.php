<html>
<head>
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
   // include the PHPReports classes on the PHP path! configure your path here
   include "src/reports/PHPReportMaker.php";
   include "config/config.php";

   $sSQL = "select  sum(inv_it_tax_amount) from si_invoice_items";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("src/reports/xml/report_tax_total.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_host");
   $oRpt->setDatabaseInterface("mysql");
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("$db_name");
   $oRpt->run();

   $error =  "<a href=\"./documentation/text/reports_xsl.html?keepThis=true&TB_iframe=true&height=300&width=650\" title=\"Info :: Reports\" class=\"thickbox\"><font color=\"red\">Got \"OOOOPS, THERE'S AN ERROR HERE.\" error?</font></a>";
   echo $error;
?>

<div id="footer"></div>
</div>
</div>
</div>
</body>
</html>

			
