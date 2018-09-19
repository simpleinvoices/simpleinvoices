<?php
/*
 *  Script: save.php
 *      Biller save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-08-10
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$op = (empty($_POST['op']) ? "" : $_POST['op']);

$saved = false;
if ( $op === 'insert_biller') {
    if (Biller::insertBiller()) $saved = true;
} else if ($op === 'edit_biller') {
    if (isset($_POST['save_biller'])) {
        if (Biller::updateBiller()) $saved = true;
    }
}

$smarty->assign('saved',$saved);

$smarty->assign('pageActive', 'biller');
$smarty->assign('active_tab', '#people');
