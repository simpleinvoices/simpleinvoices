<?php
/*
 * Script: details.php
 * Custom fields save page
 *
 * License:
 * GPL v3 or above
 *
 * Website:
 * https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Deal with op and add some basic sanity checking
$op = empty($_POST['op']) ? '' : $_POST['op'];
$saved = false;
if ($op === 'edit_custom_flag') {
    $flg_id = $_POST['flg_id'];
    // @formatter:off
    updateCustomFlags($_POST["associated_table"],
                      intval($flg_id),
                      $_POST["field_label"],
                      $_POST["enabled"], 
                      (isset($_POST["clear_custom_flags_$flg_id"]) ? $_POST["clear_custom_flags_$flg_id"] : DISABLED),
                      $_POST["field_help"]);
    // @formatter:on
    $saved = true;
}

$smarty->assign('saved', $saved);

$smarty->assign('pageActive', 'custom_flags');
$smarty->assign('active_tab', '#settings');
