<?php

class sub_customer {

    /**
     * Add extension database field if not present.
     * @return boolean true if no DB error; otherwise false.
     */
    public static function addParentCustomerId() {
        global $pdoDb;

        if (checkFieldExists(TB_PREFIX . "customers", "parent_customer_id")) return true;

        // @formatter:off
        $sql = "ALTER TABLE `" . TB.PREFIX . "customers`
                ADD `parent_customer_id` INT( 11 ) NULL AFTER `custom_field4`;";
        // @formatter:on
        try {
            $pdoDb->query($sql);
        } catch (Exception $e) {
            error_log("sub_customer.php - addParentCustomerId(): " .
                      "Unable to perform request: sql[$sql]. " . print_r($e->getMessage(),true));
            return false;
        }
        return true;
    }

    public static function insertCustomer() {
        global $config,
               $pdoDb;

        $key = $config->encryption->default->key;
        $enc = new Encryption();
        $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
        try {
            $pdoDb->setExcludedFields(array('id' => 1));
            $pdoDb->request('INSERT', 'customers');
        } catch (Exception $e) {
            echo '<h1>Unable to add the new ' . TB_PREFIX . 'customer record.</h1>';
        }
    }

    public static function updateCustomer() {
        global $config,
               $pdoDb;

        // $encrypted_credit_card_number = '';
        $excludedFields = array('id' => 1);
        if ($_POST['credit_card_number_new'] != '') {
            $key = $config->encryption->default->key;
            $enc = new Encryption();
            $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number_new']);
        } else {
            $excludedFields['credit_card_number'] = 1;
        }
        try {
            $pdoDb->setExcludedFields($excludedFields);
            $pdoDb->addSimpleWhere("id", $_GET['id']);
            $pdoDb('UPDATE', 'customers');
        } catch (Exception $e) {
            echo '<h1>Unable to update the ' . TB_PREFIX . 'customer record.</h1>';
        }
    }
}
