<?php
/*
* Script: total.php
* 	total invoice page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed

checkLogin();

$pageActive = "invoices";
$smarty->assign('pageActive', $pageActive);

include('./modules/invoices/invoice.php');

$smarty -> assign('pageActive', 'invoice_new');
$smarty -> assign('subPageActive', 'invoice_new_total');
$smarty -> assign('active_tab', '#money');
?>