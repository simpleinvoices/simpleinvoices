<?php
global $refresh_total, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

// Deal with op and add some basic sanity checking
$op = ! empty ( $_POST ['op'] ) ? addslashes ( $_POST ['op'] ) : NULL;

$saved = false;

if ($op === 'add') {
    $saved = (Expense::save());
} else if ($op === 'edit') {
    $saved =  (Expense::update());
}

if (!isset($refresh_total)) $refresh_total = '&nbsp';

$smarty->assign( 'saved', $saved );

$smarty->assign( 'pageActive', 'product_manage' );
$smarty->assign( 'active_tab', '#product' );
