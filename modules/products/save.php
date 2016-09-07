<?php
global $smarty;
// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

// Deal with op and add some basic sanity checking
$op = (!empty ( $_POST ['op'] ) ? addslashes($_POST['op']) : NULL);

$saved = false;
if ($op === 'insert_product') {
    if (Product::insertProduct()) $saved = true;
} else if ($op === 'edit_product') {
    if (isset($_POST ['save_product']) && Product::updateProduct()) $saved = true;
}

if (!isset($refresh_total)) $refresh_total = '&nbsp';
if ($refresh_total) {} // to eliminate not used warning.

$smarty->assign ( 'saved'     , $saved );
$smarty->assign ( 'pageActive', 'product_manage' );
$smarty->assign ( 'active_tab', '#product' );
