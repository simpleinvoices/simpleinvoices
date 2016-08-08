<?php

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

// @formatter:off
$expense_add = Expense::add ();
$defaults    = getSystemDefaults();
$taxes       = getActiveTaxes();

// if valid then do save
if (!empty( $_POST ['expense_account_id'] )) {
    include "./extensions/expense/modules/expense/save.php";
}

$defaults = getSystemDefaults();

$smarty->assign('domain_id'  , domain_id::get());
$smarty->assign('taxes'      , $taxes);
$smarty->assign('expense_add', $expense_add);
$smarty->assign('defaults'   , $defaults);

$smarty->assign('pageActive'   , 'expense');
$smarty->assign('subPageActive', 'add');
$smarty->assign('active_tab'   , '#money');
// @formatter:on
