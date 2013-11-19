<?php 
// Tables ticket and time_accounting OTRS project
// Ref: https://github.com/OTRS/otrs/blob/rel-3_3/scripts/database/otrs-schema.mysql.sql

//   include phpreports library
require_once("./include/reportlib.php");

$myParms=Array();

    $order = '';
    $sSQL  = "SELECT customer_id, tn, title, time_unit FROM ticket LEFT JOIN time_accounting ON ticket_id = ticket.id ";
    if (isset($_GET["tn"]) or isset($_GET["customer_id"])) {
        $sSQL .= "WHERE ";
        if (isset($_GET["tn"])) {
            $sSQL .= "tn LIKE '$_GET[tn]' ";
            $order = " ORDER BY customer_id";
        }
        if (isset($_GET["customer_id"])) {
            if (isset($_GET["tn"])) $sSQL .= "AND ";
	        else $order = " ORDER BY tn";
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
