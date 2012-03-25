<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
$SI_TAX = new SimpleInvoices_Db_Table_Tax();

$expense_add = expense::add();

$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();

$taxes = $SI_TAX->fetchAllActive();

//if valid then do save
if ($_POST['expense_account_id'] != "" ) {
	include("sys/extensions/expense/modules/expense/save.php");
}

$smarty -> assign('save',$save);
$smarty -> assign('taxes',$taxes);
$smarty -> assign('expense_add',$expense_add);
$smarty -> assign('defaults',$defaults);

$smarty -> assign('pageActive', 'expense');
$smarty -> assign('subPageActive', 'add');
$smarty -> assign('active_tab', '#money');
?>
