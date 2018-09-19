<?php
/*
 * Script: add_invoice_item.php
 *     add new invoice item in edit page
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty;
if(isset($_POST['submit'])) {
    Invoice::insertInvoiceItem($_POST['id'], $_POST['quantity1'], $_POST['product1'],
                               $_POST['tax_id'], trim($_POST['description']), $_POST['unit_price1']);
}
else {
    $products = Product::select_all();
    $smarty -> assign("products",$products);
}

$type = $_GET['type'];
$smarty->assign("type",$type);

$smarty->assign('pageActive', 'invoice');
$smarty->assign('active_tab', '#money');
