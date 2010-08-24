<?php 
//   include phpreports library
require_once("./include/reportlib.php");

$myParms=Array();


   $order = " ORDER BY customer_id";
   $sSQL  = "SELECT customer_id, tn, title, time_unit FROM ticket LEFT JOIN time_accounting ON ticket_id = ticket.id ";
   if (isSet($_GET["tn"]) or isSet($_GET["customer_id"])) {
    $sSQL .= "WHERE ";
    if (isSet($_GET["tn"])) {
     $sSQL .= "tn LIKE '$_GET[tn]' ";
     $order = " ORDER BY tn";
    }
    if (isSet($_GET["customer_id"])) {
     isSet($_GET["tn"]) && $sSQL .= "AND ";
     $sSQL .= "customer_id LIKE '$_GET[customer_id]' ";
    }
   }
   $sSQL .= $order;

$myParms["SQL"] = $sSQL;

   $oRpt->setXML("./extensions/default_invoice/modules/reports/worktime_total.xml");
   $oRpt->setUser("otrs");
   $oRpt->setPassword("test12");
   $oRpt->setDatabase("otrs2");
   $oRpt->setConnection("tux.wiwo.nl");
   $oRpt->setBody(false);
   $oRpt->setParameters($myParms);

//   include phpreports run code
	include("./include/reportrunlib.php");

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
?>
