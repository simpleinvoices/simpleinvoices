<?php

checkLogin();

$gateway    = filenameEscape($_GET['gateway'] ?? '');
$invoice_id = (int) ($_GET['invoice_id'] ?? 0);

$bladeView->assign('gateway', $gateway);
$bladeView->assign('invoice_id', $invoice_id);
$bladeView->assign('pageActive', 'invoices');
