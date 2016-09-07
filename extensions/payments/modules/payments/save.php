<?php
global $smarty, $LANG;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

if (isset($_POST['process_payment'])) {
    // @formatter:off
    $result = Payment::insert(array("ac_inv_id"       => $_POST['invoice_id'],
                                     "ac_amount"       => $_POST['ac_amount'],
                                     "ac_notes"        => $_POST['ac_notes'],
                                     "ac_date"         => sqlDateWithTime($_POST['ac_date']),
                                     "ac_payment_type" => $_POST['ac_payment_type'],
                                     "ac_check_number" => $_POST['ac_check_number']));
    $saved  = !empty($result) ? "true" : "false";

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
