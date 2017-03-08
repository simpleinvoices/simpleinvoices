<?php
/*
 * Script: save.php
 * Custom fields save page
 *
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2016-07-05 Richard Rowley
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   http://www.simpleinvoices.org
 */
global $LANG,
       $dbh,
       $auth_session,
       $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
checkLogin();
// Deal with op and add some basic sanity checking
$refresh_total = NULL;
$op = !empty($_POST['op']) ? addslashes($_POST['op']) : NULL;
if ($op === 'edit_custom_field') {
    if (isset($_POST['save_custom_field'])) {
        $clear_field = FALSE;
        $error_found = FALSE;
        // Check to see if the option to clear the value of the custom field in
        // the associated table. This can only happen if the field was changed
        // from non-blank to blank and the check box set on the custrom field
        // maintenance screen.
        if (isset($_POST['clear_data']) && $_POST['clear_data'] == "yes") {
            // There is logic on the screen that prevents the clear data field from
            // being set when the associated custom field label is not blank. However,
            // we will still verify this here just to make sure something isn't
            // mistakenly changed that allows the clear condition to be set when it shouldn't.
            if (empty($_POST['cf_custom_label'])) {
                $clear_field = TRUE;
            } else {
                $display_block = '<span class="si_message_warning">' .
                                   $LANG['clear_data'] . ' field setting is invalid. No update performed.' .
                                 '</span>';
                $error_found = TRUE;
                error_log("extensions/custom_flags/modules/custom_fields/save.php - Clear Date set when label not empty.");
                error_log("Custom Field[".$_POST['cr_custom_field']. "] Label[".$_POST['cf_custom_label']."]");
            }
        }

        if (!$error_found) {
            // @formatter:off
            $sql = "UPDATE ".TB_PREFIX."custom_fields
                    SET cf_custom_label = :label
                    WHERE cf_id     = :id
                      AND domain_id = :domain_id";
            if (dbQuery($sql        ,
                        ':id'       , $_GET['id'],
                        ':label'    , $_POST['cf_custom_label'],
                        ':domain_id', $auth_session->domain_id)) {
                if ($clear_field) {
                    // Split the value of the field name into parts and use that data to build
                    // the sql statement to clear the field in the associated table.
                    // EX: Field name is: customer_cf2. The split values are "customer" and "cf2".
                    //     The test for a missing "s" will cause the table name to be "customers".
                    //     The field name will be the constant, "custom_field", with the field number
                    //     from the end of "cf2" to be appended resulting in "custom_field2".
                    $parts = split("_", $_POST['cf_custom_field']);
                    if (count($parts) == 2 && preg_match("/cf[1-4]/", $parts[1])) {
                        // The table name part of cf_custom_field doesn't contain the needed "s" except for biller.
                        $table = $parts[0] . (preg_match("/^(customer|product|invoice)$/", $parts[0]) ? 's':'');
                        $field = "custom_field" . substr($parts[1], 2, 1);
                        $sql = "UPDATE " . TB_PREFIX . $table .
                              " SET " . $field . "=''
                                WHERE domain_id = :domain_id";
                        if (!dbQuery($sql, ':domain_id', $auth_session->domain_id)) {
                            error_log("dbQuery error: " . $dbh->errorInfo() . " for sql[$sql]");
                        }
                    }
                }
                $display_block  =  $LANG['save_custom_field_success'];
            } else {
                $display_block  =  '<span class="si_message_warning">' .
                                      $LANG['save_custom_field_failure'] . end($dbh->errorInfo()) .
                                   '</span>';
            }
            // @formatter:on
            $refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=custom_fields&amp;view=manage' />";
        }
    } else if (isset($_POST['cancel'])) {
        $refresh_total = "<meta http-equiv='refresh' content='0;url=index.php?module=custom_fields&amp;view=manage' />";
    }
}

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_total', $refresh_total);

$smarty->assign('pageActive', 'custom_field');
$smarty->assign('active_tab', '#setting');
