<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Add check_number field to the database if not present.
require_once "./extensions/payments/include/class/payment.php";
payment::addNewFields();

$maxInvoice = maxInvoice();

$paymentTypes = getActivePaymentTypes();
$chk_pt = 0;
foreach ($paymentTypes as $ptyp) {
    if (strtolower($ptyp['pt_description']) == 'check') {
        $chk_pt = trim($ptyp['pt_id']);
        break;
    }
}

// Generate form validation script
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateifNum("ac_amount",$LANG['amount']);
jsValidateifNum("ac_date",$LANG['date']);
echo "if(theForm.ac_payment_type.value=='$chk_pt') {\n";
echo "    var cknum = theForm.ac_check_number.value;\n";
echo "    cknum = cknum.toUpperCase();\n";
echo "    if(!(/^[1-9][0-9]* *$/).test(cknum) && cknum != 'N/A') {\n";
echo "        alert('Enter a valid Check Number, \"N/A\" or change the Payment Type.');\n";
echo "        theForm.ac_check_number.focus();\n";
echo "        return (false);\n";
echo "    };\n";
echo "    theForm.ac_check_number.value = cknum;\n";
echo "}\n";
//jsValidateifNum("ac_check_number",$LANG['check_number']);
jsFormValidationEnd();
jsEnd();
// end validation generation

$today = date("Y-m-d");

$invoice = null;

if(isset($_GET['id'])) {
	$invoiceobj = new invoice();
	$invoice = $invoiceobj->select($_GET['id']);
} else {
	$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id', domain_id::get());
    $invoice = $sth->fetch(PDO::FETCH_ASSOC);
}

// @formatter:off
$customer     = getCustomer($invoice['customer_id']);
$biller       = getBiller($invoice['biller_id']);
$defaults     = getSystemDefaults();
$pt           = getPaymentType($defaults['payment_type']);
// @formatter:on

$invoices = new invoice();
$invoices->sort='id';
$invoices->having='money_owed';
$invoices->having_and='real';
$invoice_all = $invoices->select_all('count');

$smarty->assign('invoice_all',$invoice_all);

// @formatter:off
$smarty->assign("paymentTypes", $paymentTypes);
$smarty->assign("defaults"    , $defaults);
$smarty->assign("biller"      , $biller);
$smarty->assign("customer"    , $customer);
$smarty->assign("invoice"     , $invoice);
$smarty->assign("today"       , $today);

$subPageActive =  "payment_process" ;
$smarty->assign('pageActive'   , 'payment');
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab'   , '#money');
// @formatter:on

