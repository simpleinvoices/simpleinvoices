<?php
global $smarty, $LANG, $pdoDb;
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Add check_number field to the database if not present.
require_once "./extensions/payments/include/class/CheckNumber.php";
CheckNumber::addNewFields();

$paymentTypes = PaymentType::select_all(true);
$chk_pt = 0;
foreach ($paymentTypes as $ptyp) {
    if (preg_match('/^check$/iD', $ptyp['pt_description'])) {
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
jsFormValidationEnd();
jsEnd();
// end validation generation

$today = date("Y-m-d");
$invoice = null;
if(isset($_GET['id'])) {
    $invoice = Invoice::select($_GET['id']);
} else {
    $pdoDb->addSimpleWhere("domain_id", domain_id::get());
    $rows = $pdoDb->request("SELECT", "invoices");
    $invoice = $rows[0];
}

// @formatter:off
$customer = Customer::get($invoice['customer_id']);
$biller   = Biller::select($invoice['biller_id']);
$defaults = getSystemDefaults();
// @formatter:on

$pdoDb->setHavings(Invoice::buildHavings("money_owed"));
$invoice_all = Invoice::select_all("count", "", null, "", "", "");

$smarty->assign('invoice_all',$invoice_all);

// @formatter:off
$smarty->assign("paymentTypes", $paymentTypes);
$smarty->assign("defaults"    , $defaults);
$smarty->assign("biller"      , $biller);
$smarty->assign("customer"    , $customer);
$smarty->assign("invoice"     , $invoice);
$smarty->assign("today"       , $today);

$smarty->assign('pageActive'   , 'payment');
$smarty->assign('subPageActive', "payment_process");
$smarty->assign('active_tab'   , '#money');
// @formatter:on
