<?php
/*
 *  Script: save.php
 *      Biller save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-01-16 by Rich Rowley to add signature field
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty, $pdoDb;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Deal with op and add some basic sanity checking
$op = (empty($_POST['op']) ? "" : $_POST['op']);

$saved = false;
if ($op === 'insert_biller') {
    // From add.tpl
    try {
        $pdoDb->setExcludedFields('id');
        $pdoDb->request('INSERT', 'biller');
        $saved = true;
    } catch (Exception $e) {
        echo '<h1>Unable to add the new ' . TB_PREFIX . 'biller record.</h1>';
    }
} elseif ($op === 'edit_biller' && isset($_POST['save_biller'])) {
    try {
        $where = new WhereClause();
        $where->addItem(new WhereItem(false, 'id', '=', $_GET['id'], false, 'AND'));
        $where->addItem(new WhereItem(false, 'domain_id', '=', $_POST['domain_id'], false));

        $pdoDb->addToWhere($where);
        $pdoDb->setExcludedFields(array('id', 'domain_id'));
        $pdoDb->request('UPDATE', 'biller');
        $saved = true;
    } catch (Exception $e) {
        error_log("Unable to update the " . TB_PREFIX . "biller record. Error reported: " . $e->getMessage());
    }
}

$smarty->assign('saved', $saved);

$smarty->assign('pageActive', 'biller');
$smarty->assign('active_tab', '#people');
