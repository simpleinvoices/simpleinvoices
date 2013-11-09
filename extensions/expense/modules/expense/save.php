<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product
$saved = false;

$expenseobj = new expense();

if (  $op === 'add' ) 
{
	
	if( $expenseobj->save() ) 
    {

 		$saved = true;

 	}

}

if ($op === 'edit') 
{

	if ( $expenseobj->update() ) 
    {

		$saved = true;

	}

}

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty->assign('saved',$saved);

$smarty -> assign('pageActive', 'product_manage');
$smarty -> assign('active_tab', '#product');
?>
