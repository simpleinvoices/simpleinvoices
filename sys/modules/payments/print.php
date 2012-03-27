<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PAYMENT_TYPES = new SimpleInvoices_Db_Table_PaymentTypes();
$SI_INVOICE_TYPE = new SimpleInvoices_Db_Table_InvoiceType();
$SI_BILLER = new SimpleInvoices_Db_Table_Biller();
$SI_PREFERENCES = new SimpleInvoices_Db_Table_Preferences();
$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

$menu = false;
$payment = getPayment($_GET['id']);

/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$invoice = getInvoice($payment['ac_inv_id']);
$biller = $SI_BILLER->getBiller($payment['biller_id']);
$logo = getLogo($biller);
$logo = str_replace(" ", "%20", $logo);
$customer = customer::get($payment['customer_id']);
$invoiceType = $SI_INVOICE_TYPE->getInvoiceType($invoice['type_id']);
$customFieldLabels = $SI_CUSTOM_FIELDS->getLabels();
$paymentType = $SI_PAYMENT_TYPES->getPaymentTypeById($payment['ac_payment_type']);
$preference = $SI_PREFERENCES->getPreferenceById($invoice['preference_id']);

$smarty -> assign("payment",$payment);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("biller",$biller);
$smarty -> assign("logo",$logo);
$smarty -> assign("customer",$customer);
$smarty -> assign("invoiceType",$invoiceType);
$smarty -> assign("paymentType",$paymentType);
$smarty -> assign("preference",$preference);
$smarty -> assign("customFieldLabels",$customFieldLabels);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
