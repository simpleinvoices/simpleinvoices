<?php
global $smarty, $LANG;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Add check_number field to the database if not present.
require "./extensions/payments/include/class/payment.php";

if (isset($_POST['process_payment'])) {
    // @formatter:off
    $payment = new payment();
    $payment->ac_inv_id       = $_POST['invoice_id'];
    $payment->ac_amount       = $_POST['ac_amount'];
    $payment->ac_notes        = $_POST['ac_notes'];
    $payment->ac_date         = sqlDateWithTime($_POST['ac_date']);
    $payment->ac_payment_type = $_POST['ac_payment_type'];
    $payment->ac_check_number = $_POST['ac_check_number'];
    $result                   = $payment->insert();
    $saved                    = !empty($result) ? "true" : "false";
    // @formatter:on
    if ($saved == 'true') {
        $display_block = "<div class='si_message_ok'>$LANG[save_payment_success]</div>";
    } else {
        $display_block = "<div class='si_message_error'>$LANG[save_payment_failure]</div>";
    }

    $refresh_total = "<meta http-equiv='refresh' content='27;url=index.php?module=payments&view=manage' />";
    if ($refresh_total) {} // to eliminate not used warning
}

$smarty->assign('display_block', $display_block);
$smarty->assign('pageActive'   , 'payment');
$smarty->assign('active_tab'   , '#money');
