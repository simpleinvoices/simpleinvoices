<?php
/*
* Script: ./extensions/matts_luxury_pack/modules/customers/save.php
* 	Customers save page
*
* Authors:
*	 yumatechnical@gmail.com
*
* Last edited:
* 	 2016-08-29
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
global $config, $smarty, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

$defaults = getSystemDefaults();//Matt
$saved = false;
$error = false;
$excludedFields = array("id" => 1);
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
        error_log("Unable to encrypt the credit card number. Error reported: " . $e->getMessage());
        $error = true;
    }
}

if (!$error) {
    if ($op === "insert_customer") {
        try {
/*			if ($defaults['price_list'] && insert_Customer()) {// do insert_Customer() if $defaults['price_list'] enabled
				// saveCustomFieldValues($_POST['categorie'],lastInsertId());
			} else {*/
				$pdoDb->setExcludedFields($excludedFields);
				$pdoDb->request('INSERT', 'customers');
/*			}*/
			$saved = true;
        } catch (Exception $e) {
            echo '<h1>Unable to add the new ' . TB_PREFIX . 'customer record.</h1>';
            error_log("Unable to add the new " . TB_PREFIX . "customers record. Error reported: " . $e->getMessage());
            $error = true;
        }
    } else if ($op === 'edit_customer' && isset($_POST['save_customer'])) {
        try {
/*			if ($defaults['price_list'] && update_Customer()) {// do update_Customer() if $defaults['price_list'] enabled
				//updateCustomFieldValues($_POST['categorie'],$_GET['customer']);
			} else {
				// saveCustomFieldValues($_POST['categorie'],lastInsertId());
*/
				$excludedFields['domain_id'] = 1;
				$pdoDb->setExcludedFields($excludedFields);
				$pdoDb->addSimpleWhere('id', $_GET['id'], 'AND');
				$pdoDb->addSimpleWhere('domain_id', isset($_POST['domain_id']) ? $_POST['domain_id'] : 1 );
				$pdoDb->request('UPDATE', 'customers');
				$saved = true;
/*			}*/
        } catch (Exception $e) {
            echo '<h1>Unable to update the ' . TB_PREFIX . 'customers record.</h1>';
            error_log("Unable to update the " . TB_PREFIX . "customers record. Error reported: " . $e->getMessage());
            $error = true;
        }
    }
}

$smarty->assign('saved',$saved);

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
/*if (isset($_POST['last_id']))
	$smarty -> assign('last_id',$_POST['last_id']);*/
