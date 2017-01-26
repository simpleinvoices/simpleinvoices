<?php
global $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
checkLogin();

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? addslashes($_POST['op']) : NULL;

$saved = false;
if ($op === 'insert_payment_type') {
    if (PaymentType::insert(domain_id::get(), $_POST['pt_description'], $_POST['pt_enabled']) > 0) $saved = true;
} else if ($op === 'edit_payment_type') {
    if (isset($_POST['save_payment_type'])) {
        $saved = PaymentType::update($_GET['id'], $_POST['pt_description'], $_POST['pt_enabled']);
    }
}

$refresh_total = '&nbsp';

$smarty->assign('refresh_total', $refresh_total);
$smarty->assign('saved', $saved);

$smarty->assign('pageActive', 'payment_type');
$smarty->assign('active_tab', '#setting');
