<?php 
include("./include/include_main.php"); 

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

?>
<b>Sales in total</b>
<hr></hr>

<div id="container">

<?php
include('./config/config.php');

   // include the PHPReports classes on the PHP path! configure your path here
   include "./src/reports/PHPReportMaker.php";

   $sSQL = "select sum(inv_it_total) from {$tb_prefix}invoice_items";
   $oRpt = new PHPReportMaker();

   $oRpt->setXML("src/reports/xml/report_sales_total.xml");
   $oRpt->setUser("$db_user");
   $oRpt->setPassword("$db_password");
   $oRpt->setConnection("$db_host");
   $oRpt->setDatabaseInterface("mysql");
   $oRpt->setSQL($sSQL);
   $oRpt->setDatabase("$db_name");
   $oRpt->run();
?>

<a href="./src/documentation/info_pages/reports_xsl.html" rel="gb_page_center[450, 450]"><font color="red">Did you get an "OOOOPS, THERE'S AN ERROR HERE." error?</font></a>
<hr></hr>
</div>
<div id="footer"></div>


