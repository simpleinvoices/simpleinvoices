<?php

checkLogin();

$invoice_id = (int)$_GET['id'];
$invoice = getInvoice($invoice_id);
si_check_invoice_access($invoice);

$inv = new invoice();
$inv->id = $invoice_id;
$inv->domain_id = $auth_session->domain_id;

$new_id = $inv->recur();

if ($new_id) {
	invoice_denorm::refreshForInvoice((int) $new_id, $auth_session->domain_id);
	dashboard_cache_clear((int) $auth_session->domain_id);
}

$saved = ($new_id !== false);
$bladeView->assign('saved', $saved);
$bladeView->assign('new_id', $new_id);
$bladeView->assign('pageActive', 'invoice');
$bladeView->assign('active_tab', '#money');