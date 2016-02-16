<?php
/*
 * Script: save.php
 * Biller save page
 *
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 2016-01-16 by Rich Rowley to add signature field
 * 2007-07-19
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * http://www.simpleinvoices.org
 */

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? addslashes($_POST['op']) : NULL;

$saved = false;

// @formatter:off
if ($op === 'insert_biller') {
    // From add.tpl
    try {
        pdoRequest('INSERT', 'biller', array('id'));
        $saved = true;
    } catch (Exception $e) {
        echo '<h1>Unable to add the new ' . TB_PREFIX . 'biller record.</h1>';
    }
} elseif ($op === 'edit_biller' && isset($_POST['save_biller'])) {
    try {
        $whereClause = new WhereClause();
        $whereClause->addItem(new WhereItem(false, 'id', '=', $_POST['id'], false, 'AND'));
        $whereClause->addItem(new WhereItem(false, 'domain_id', '=', $_POST['domain_id'], false));
        
        pdoRequest('UPDATE', 'biller', array('id' => ':id', 'domain_id' => ':domain_id'), $whereClause);
        $saved = true;
    } catch (Exception $e) {
        error_log("Unable to update the " . TB_PREFIX . "biller record. Error reported: " . $e->getMessage());
    }
}

$smarty->assign('saved'     , $saved);
$smarty->assign('pageActive', 'biller');
$smarty->assign('active_tab', '#people');
// @formatter:on
