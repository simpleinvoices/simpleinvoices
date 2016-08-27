<?php
/*
* Script: /simple/extensions/invoice_add_display_no/modules/invoices/manage.php
* 	Manage Invoices page
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
global $pagerows;
$pageActive = "invoice";

//$smarty -> assign("number_of_invoices", $myinvoice->numberof());
$smarty -> assign("number_of_invoices", ninvoices());
$smarty -> assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#money');

$having="";
if (isset($_GET['having']))
{
    $having = "&having=".$_GET['having'];
}
$url =  'index.php?module=invoices&view=xml'.$having;

$smarty -> assign('url', $url);

$defaults = getSystemDefaults();
$smarty->assign ("d", $defaults['default_nrows']);
$smarty->assign ("array", $pagerows);
