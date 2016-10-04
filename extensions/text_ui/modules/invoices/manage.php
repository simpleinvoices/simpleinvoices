<?php
/*
 * Script: manage.php
 * Manage Invoices page
 *
 * License:
 * GPL v3 or above
 *
 * Website:
 * http://www.simpleinvoices.org
 */

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$path1 = './extensions/text_ui/include';
$curr_path = get_include_path();
if (!strstr($curr_path, $path1)) {
    $path2 = $path1 . '/class';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path1 . PATH_SEPARATOR . $path2);
}

// Create & initialize DB table if it doesn't exist.
$invoices = TextUiInvoice::getInvoiceItems();
$number_of_invoices = array_shift($invoices);

$pageActive = "invoice";

$smarty->assign("invoices"          , $invoices);
$smarty->assign("number_of_invoices", $number_of_invoices);
$smarty->assign("spreadsheet"       , $spreadsheet);
$smarty->assign("word_processor"    , $word_processor);
$smarty->assign('pageActive'        , $pageActive);

$page      = empty($_GET['page']) ? "1" : $_GET['page'];
$page_prev = ($page == "1") ? "1" : $page - 1;
$page_next = $page + 1;

$url = getURL();
$xml_file = $url . '/index.php?module=invoices&view=xml&page=' . $page;
$smarty->assign('xml', $xml_file);

$smarty->assign('page', $page);
$smarty->assign('page_prev', $page_prev);
$smarty->assign('page_next', $page_next);
// @formatter:on
?>
