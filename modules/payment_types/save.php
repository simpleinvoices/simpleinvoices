<?php
global $db_server, $auth_session, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
checkLogin();

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? addslashes($_POST['op']) : NULL;

$saved = false;
if ($op === 'insert_payment_type') {
    // $formatter:off
    if ($db_server == 'pgsql') {
        $sql = "INSERT into " . TB_PREFIX . "payment_types
                       (domain_id  , pt_description, pt_enabled)
                VALUES (:domain_id', :description  , :enabled)";
    } else {
        $sql = "INSERT INTO " . TB_PREFIX . "payment_types
                       (domain_id , pt_description, pt_enabled)
                VALUES (:domain_id, :description  , :enabled)";
    }

    if (dbQuery($sql, ':domain_id'  , $auth_session->domain_id,
                      ':description', $_POST['pt_description'],
                      ':enabled'    , $_POST['pt_enabled'])) {
        $saved = true;
    }
    // @formatter:on
} else if ($op === 'edit_payment_type') {
    if (isset($_POST['save_payment_type'])) {
        // @formatter:off
        $sql = "UPDATE " . TB_PREFIX . "payment_types
                SET pt_description = :description,
                    pt_enabled = :enabled
                WHERE pt_id = :id";
        if (dbQuery($sql, ':description', $_POST['pt_description'],
                          ':enabled'    , $_POST['pt_enabled'],
                          ':id'         , $_GET['id'])) {
            $saved = true;
        }
        // @formatter:on
    }
}

$refresh_total = '&nbsp';

$smarty->assign('refresh_total', $refresh_total);
$smarty->assign('saved', $saved);

$smarty->assign('pageActive', 'payment_type');
$smarty->assign('active_tab', '#setting');
