<?php
/*
* Script: itemised.php
* 	itemised invoice page
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

$logger->log('Itemised invoice created', LegacyLogger::INFO);

include('./modules/invoices/invoice.php');

$bladeView -> assign('pageActive', 'invoice_new');
$bladeView -> assign('subPageActive', 'invoice_new_itemised');
$bladeView -> assign('active_tab', '#money');
?>
