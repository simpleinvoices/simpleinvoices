<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$menu    = false;
$payment = getPayment($_GET['id']);

// Get Invoice preference - so can link from this screen back to the invoice
$invoice           = getInvoice($payment['ac_inv_id']);
$biller            = getBiller($payment['biller_id']);
$customer          = getCustomer($payment['customer_id']);
$invoiceType       = getInvoiceType($invoice['type_id']);
$customFieldLabels = getCustomFieldLabels('',true);
$paymentType       = getPaymentType($payment['ac_payment_type']);
$preference        = getPreference($invoice['preference_id']);
$logo              = getLogo($biller);
$logo              = str_replace(" ", "%20", $logo);

$lang = $smarty->get_template_vars('LANG');

$biller_info = array();
$biller_info[] = array($lang['name'].':',$biller['name']);
$needs_lbl=true;
if (!empty($biller['street_address'])) {
    $biller_info[] = array($lang['address'].':',$biller['street_address']);
    $needs_lbl = false;
}
if (!empty($biller['street_address2'])) {
    $biller_info[] = array(($needs_lbl ? $lang['address'].':' : ''),$biller['street_address2']);
    $needs_lbl = false;
}
if (!empty($biller['city']) || !empty($biller['state']) || !empty($biller['zip_code'])) {
    // @formatter:off
    $tmp_lin = "";
    if (!empty($biller['city'])    ) $tmp_lin .= $biller['city'] . ', ';
    if (!empty($biller['state'])   ) $tmp_lin .= $biller['state'] . ' ';
    if (!empty($biller['zip_code'])) $tmp_lin .= $biller['zip_code'];

    $biller_info[] = array(($needs_lbl ? $lang['address'].':' : ""), $tmp_lin);
    $needs_lbl     = false;
    // @formatter:on
}
if (!empty($biller['country'])) {
    $biller_info[] = array(($needs_lbl ? $lang['address'].':' : ''), $biller['country']);
}

if (!empty($biller['phone'])        ) $biller_info[] = array($lang['phone']       , $biller['phone']);
if (!empty($biller['fax'])          ) $biller_info[] = array($lang['fax']         , $biller['fax']);
if (!empty($biller['mobile_phone']) ) $biller_info[] = array($lang['mobile_short'], $biller['mobile_phone']);
if (!empty($biller['email'])        ) $biller_info[] = array($lang['email']       , $biller['email']);

if (!empty($customFieldLabels['biller_cf1']) &&
    !empty($biller['custom_field1'])) $biller_info[] = array($customFieldLabels['biller_cf1'], $biller['custom_field1']);

if (!empty($customFieldLabels['biller_cf2']) &&
    !empty($biller['custom_field2'])) $biller_info[] = array($customFieldLabels['biller_cf2'], $biller['custom_field2']);

if (!empty($customFieldLabels['biller_cf3']) &&
    !empty($biller['custom_field3'])) $biller_info[] = array($customFieldLabels['biller_cf3'], $biller['custom_field3']);

if (!empty($customFieldLabels['biller_cf4']) &&
    !empty($biller['custom_field4'])) $biller_info[] = array($customFieldLabels['biller_cf4'], $biller['custom_field4']);

$biller_info_count = count($biller_info);

$cust_info = array();
$cust_info[] = array($lang['customer'].':', $customer['name']);
if (!empty($customer['attention'])) {
   $cust_info[] = array($lang['attention_short'].':',$customer['attention']);
}
$needs_lbl=true;
if (!empty($customer['street_address'])) {
    $cust_info[] = array($lang['address'].':',$customer['street_address']);
    $needs_lbl = false;
}
if (!empty($customer['street_address2'])) {
    $cust_info[] = array(($needs_lbl ? $lang['address'].':' : ''),$customer['street_address2']);
    $needs_lbl = false;
}
if (!empty($customer['city']) || !empty($customer['state']) || !empty($customer['zip_code'])) {
    // @formatter:off
    $tmp_lin = "";
    if (!empty($customer['city'])    ) $tmp_lin .= $customer['city'] . ', ';
    if (!empty($customer['state'])   ) $tmp_lin .= $customer['state'] . ' ';
    if (!empty($customer['zip_code'])) $tmp_lin .= $customer['zip_code'];

    $cust_info[] = array(($needs_lbl ? $lang['address'].':' : ""), $tmp_lin);
    $needs_lbl   = false;
    // @formatter:on
}
if (!empty($customer['country'])) {
    $cust_info[] = array(($needs_lbl ? $lang['address'].':':''), $customer['country']);
}

if (!empty($customer['phone'])        ) $cust_info[] = array($lang['phone']       , $customer['phone']);
if (!empty($customer['fax'])          ) $cust_info[] = array($lang['fax']         , $customer['fax']);
if (!empty($customer['mobile_phone']) ) $cust_info[] = array($lang['mobile_short'], $customer['mobile_phone']);
if (!empty($customer['email'])        ) $cust_info[] = array($lang['email']       , $customer['email']);

if (!empty($customFieldLabels['customer_cf1']) &&
    !empty($customer['custom_field1'])) $cust_info[] = array($customFieldLabels['customer_cf1'], $customer['custom_field1']);

if (!empty($customFieldLabels['customer_cf2']) &&
    !empty($customer['custom_field2'])) $cust_info[] = array($customFieldLabels['customer_cf2'], $customer['custom_field2']);

if (!empty($customFieldLabels['customer_cf3']) &&
    !empty($customer['custom_field3'])) $cust_info[] = array($customFieldLabels['customer_cf3'], $customer['custom_field3']);

if (!empty($customFieldLabels['customer_cf4']) &&
    !empty($customer['custom_field4'])) $cust_info[] = array($customFieldLabels['customer_cf4'], $customer['custom_field4']);

$cust_info_count = count($cust_info);

$smarty->assign("payment"          , $payment);
$smarty->assign("invoice"          , $invoice);
$smarty->assign("biller"           , $biller);
$smarty->assign("biller_info"      , $biller_info);
$smarty->assign("biller_info_count", $biller_info_count);
$smarty->assign("logo"             , $logo);
$smarty->assign("customer"         , $customer);
$smarty->assign("cust_info"        , $cust_info);
$smarty->assign("cust_info_count"  , $cust_info_count);
$smarty->assign("invoiceType"      , $invoiceType);
$smarty->assign("paymentType"      , $paymentType);
$smarty->assign("preference"       , $preference);
$smarty->assign("customFieldLabels", $customFieldLabels);
$smarty->assign('pageActive'       , 'payment');
$smarty->assign('active_tab'       , '#money');
// @formatter:on
