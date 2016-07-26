<?php
/*
 * Script: save.php
 *     Customers save page
 *
 * Authors:
 *     Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *      2007-07-19
 *
 * License:
 *     GPL v2 or above
 *
 * Website:
 *     http://www.simpleinvoices.org
 */
global $config, $smarty, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

$saved = false;
if ($op === "insert_customer") {
    try {
        $key = $config->encryption->default->key;
        $enc = new Encryption();
        $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
        $pdoDb->setExcludedFields(array('id' => 1));
        $pdoDb('INSERT', 'customers');
        $saved = true;
    } catch (Exception $e) {
        echo '<h1>Unable to add the new ' . TB_PREFIX . 'customer record.</h1>';
    }
} else if ($op === 'edit_customer' && isset($_POST['save_customer'])) {
    $excludedFields = array('id' => 1, 'domain_id' => 1);
    // The field is only non-empty if the user entered a value.
    // TODO: A proper entry and confirmation new credit card value.
    if (empty($_POST['credit_card_number'])) {
        $excludedFields['credit_card_number'] = 1;
    } else {
        try {
            $key = $config->encryption->default->key;
            $enc = new Encryption();
            $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
        } catch (Exception $e) {
            echo '<h1>Unable to encrypt the card number.</h1>';
        }
    }

    try {
        $pdoDb->setExcludedFields($excludedFields);
        $pdoDb->addSimpleWhere('id', $_GET['id'], 'AND');
        $pdoDb->addSimpleWhere('domain_id', $_POST['domain_id']);
        $pdoDb->request('UPDATE', 'customers');
        $saved = true;
    } catch (Exception $e) {
        error_log("Unable to update the " . TB_PREFIX . "customers record. Error reported: " . $e->getMessage());
        echo '<h1>Unable to update the ' . TB_PREFIX . 'customers record.</h1>';
    }
}

$smarty->assign('saved',$saved);

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
