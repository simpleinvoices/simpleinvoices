<?php
/*
 * Script: manage.php
 * Manage Invoices page
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * https://simpleinvoices.group
 */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$smarty->assign("number_of_invoices", Invoice::count());

$having = "";
if (isset($_GET['having'])) {
    $having = "&having=" . $_GET['having'];
}
$url = 'index.php?module=invoices&view=xml' . $having;
$smarty->assign('url', $url);

$smarty->assign('pageActive', "invoice");
$smarty->assign('active_tab', '#money');
