<?php
global $smarty, $LANG;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$expense_id  = $_GET['id'];

$rows = Expense::get($expense_id);
$expense = $rows[0];

$detail  = Expense::detail();
$detail['customer']            = Customer::get($expense['customer_id']);
$detail['biller']              = Biller::select($expense['biller_id']);
$detail['invoice']             = Invoice::select($expense['invoice_id']);
$detail['product']             = Product::select($expense['product_id']);
$detail['expense_account']     = ExpenseAccount::select($expense['expense_account_id']);
$detail['expense_tax']         = ExpenseTax::get_all($expense_id);
$detail['expense_tax_total']   = $expense['amount'] + ExpenseTax::get_sum($expense_id);
$detail['expense_tax_grouped'] = ExpenseTax::grouped($expense_id);
$detail['status_wording']      = ($expense['status'] == 1 ? $LANG['paid'] : $LANG['not_paid']);

$taxes = Taxes::getActiveTaxes();
$defaults = getSystemDefaults();

$smarty->assign('expense' ,$expense);
$smarty->assign('detail'  ,$detail);
$smarty->assign('taxes'   ,$taxes);
$smarty->assign('defaults',$defaults);

$smarty->assign('pageActive', 'expense');

$subPageActive = $_GET['action'] =="view"  ? "view" : "edit" ;

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#money');
// @formatter:on
