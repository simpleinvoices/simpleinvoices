<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product
$saved = false;

if (  $op === 'insert' ) {
	
	if(expenseaccount::insert()) {
 		$saved = true;
 		//saveCustomFieldValues($_POST['categorie'], lastInsertId());
 	}
}

if ($op === 'edit' ) {
	if (expenseaccount::update()) {
		$saved = true;
	}
}

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty->assign('saved',$saved);

$smarty -> assign('pageActive', 'expense_account_manage');
$smarty -> assign('active_tab', '#money');
?>
