<?php
global $smarty, $LANG;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$expense_id        = $_GET['id'];
$expenseobj        = new expense();
$customerobj       = new customer();
$billerobj         = new biller();
$invoiceobj        = new invoice();
$productobj        = new product();
$expenseaccountobj = new expenseaccount();
$expensetaxobj     = new expensetax();

$expense = $expenseobj->get($expense_id);
$detail  = $expenseobj->detail();
$detail['customer']            = $customerobj->get($expense['customer_id']);
$detail['biller']              = $billerobj->select($expense['biller_id']);
$detail['invoice']             = $invoiceobj->select($expense['invoice_id']);
$detail['product']             = $productobj->get($expense['product_id']);
$detail['expense_account']     = $expenseaccountobj->select($expense['expense_account_id']);
$detail['expense_tax']         = $expensetaxobj->get_all($expense_id);
$detail['expense_tax_total']   = $expense['amount'] + $expensetaxobj->get_sum($expense_id);
$detail['expense_tax_grouped'] = $expensetaxobj->grouped($expense_id);
$detail['status_wording']      = $expense['status']==1?$LANG['paid']:$LANG['not_paid'];

$taxes = getActiveTaxes();
//$tax_selected = getTaxRate($product['default_tax_id']);
$defaults = getSystemDefaults();

$smarty->assign('expense' ,$expense);
$smarty->assign('detail'  ,$detail);
$smarty->assign('taxes'   ,$taxes);
$smarty->assign('defaults',$defaults);
//$smarty -> assign('tax_selected',$tax_selected);
//$smarty -> assign('customFieldLabel',$customFieldLabel);

$smarty->assign('pageActive', 'expense');

$subPageActive = $_GET['action'] =="view"  ? "view" : "edit" ;
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#money');
