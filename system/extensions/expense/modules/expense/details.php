<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$expense_id = $_GET['id'];

$expense = expense::get($expense_id);
$detail = expense::detail();
$detail['customer'] = customer::get($expense['customer_id']);
$detail['biller'] = biller::select($expense['biller_id']);
$detail['invoice'] = invoice::select($expense['invoice_id']);
$detail['product'] = product::get($expense['product_id']);
$detail['expense_account'] = expenseaccount::select($expense['expense_account_id']);
$detail['expense_tax'] = expensetax::get_all($expense_id);
$detail['expense_tax_total'] = $expense['amount'] + expensetax::get_sum($expense_id);
$detail['expense_tax_grouped'] = expensetax::grouped($expense_id);
$detail['status_wording'] = $expense['status']==1?$LANG['paid']:$LANG['not_paid'];

$taxes = getActiveTaxes();
#$tax_selected = getTaxRate($product['default_tax_id']);
$defaults = getSystemDefaults();

$smarty -> assign('expense',$expense);
$smarty -> assign('detail',$detail);
$smarty -> assign('taxes',$taxes);
$smarty -> assign('defaults',$defaults);
$smarty -> assign('tax_selected',$tax_selected);
$smarty -> assign('customFieldLabel',$customFieldLabel);

$smarty -> assign('pageActive', 'expense');
$subPageActive = $_GET['action'] =="view"  ? "view" : "edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#money');
?>
