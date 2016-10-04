<?php
/*
 *  Script: details.php
 *      invoice details page
 *
 *  Author:
 *      Marcel van Dorp.
 *
 *  Last modified
 *      2016-08-02
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      http://www.simpleinvoices.org
 */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

$default_template_set = (!empty($_GET['template']));

$master_invoice_id = ($default_template_set ? $_GET ['template'] : $_GET ['id']);

$invoice = Invoice::getInvoice( $master_invoice_id );
if ($default_template_set) $invoice['id'] = null;

$invoiceobj = new Invoice();
$invoiceItems = $invoiceobj->getInvoiceItems ( $master_invoice_id );

$customers   = Customer::get_all(true);
$preference  = getPreference ( $invoice ['preference_id'] );
$billers     = Biller::get_all(true);
$defaults    = getSystemDefaults ();
$taxes       = getTaxes ();
$preferences = getActivePreferences ();
$products    = Product::select_all ();

$customFields = array();
for($i = 1; $i <= 4; $i ++) {
    $customFields[$i] = show_custom_field( "invoice_cf$i", $invoice ["custom_field$i"], "write", '', "details_screen", '', '', '' );
}

$smarty->assign ( "invoice"     , $invoice );
$smarty->assign ( "defaults"    , $defaults );
$smarty->assign ( "invoiceItems", $invoiceItems );
$smarty->assign ( "customers"   , $customers );
$smarty->assign ( "preference"  , $preference );
$smarty->assign ( "billers"     , $billers );
$smarty->assign ( "taxes"       , $taxes );
$smarty->assign ( "preferences" , $preferences );
$smarty->assign ( "products"    , $products );
$smarty->assign ( "customFields", $customFields );
$smarty->assign ( "lines"       , count ( $invoiceItems ) );

$smarty->assign ( 'pageActive', 'invoice' );
$smarty->assign ( 'active_tab', '#money' );
