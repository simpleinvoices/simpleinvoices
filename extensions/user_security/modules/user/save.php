<?php
/*
 * Script: save.tpl
 *   User save template
 *
 * Authors:
 *  Justin Kelly, Nicolas Ruflin,
 *  Rich Rowley
 *
 * Last edited:
 *    2016-05-28
 *
 * License:
 *  GPL v3 or above
 */
global $smarty,
       $pdoDb;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? addslashes($_POST['op']) : NULL;
$saved = false;
$ok = true;
if (!empty($_POST['password'])) {
    if (empty($_POST['confirm_password']) || $_POST['password'] != $_POST['confirm_password']) {
        $smarty->assign('confirm_error', 'Password and Confirm Password do not match.');
        $ok = false;
    } else {
        $_POST['password'] = MD5($_POST['password']); // OK. Save hashed password
    }
}

// @formatter:off
if ($ok) {
    if ($op === 'insert_user') {
        $_POST['domain_id'] = domain_id::get();
        // From add.tpl
        try {
            $pdoDb->setExcludedFields(array('id' => 1));
            $pdoDb->request('INSERT', 'user');
            $saved = true;
        } catch (Exception $e) {
            echo '<h1>Unable to add the new ' . TB_PREFIX . 'user record.</h1>';
        }
    } elseif ($op === 'edit_user' && isset($_POST['save_user'])) {
        try {
            $pdoDb->setExcludedFields(array('id' => 1, 'domain_id' => 1));
            $pdoDb->addSimpleWhere('id', $_GET['id'], 'AND');
            $pdoDb->addSimpleWhere('domain_id', $_POST['domain_id']);
            $pdoDb->request('UPDATE', 'user');
            $saved = true;
        } catch (Exception $e) {
            error_log("Unable to update the " . TB_PREFIX . "user record. Error reported: " . $e->getMessage());
            echo '<h1>Unable to update the ' . TB_PREFIX . 'user record.</h1>';
        }
    }
}

$smarty->assign('saved', $saved);

$smarty->assign('pageActive', 'user');
$smarty->assign('active_tab', '#people');
