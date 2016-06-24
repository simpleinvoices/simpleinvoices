<?php

class sub_customer {

    /**
     * Add extension database field if not present.
     * @return boolean true if no DB error; otherwise false.
     */
    public static function addParentCustomerId() {
        global $dbh;

        if (checkFieldExists(TB_PREFIX . "customers", "parent_customer_id")) return true;

        // @formatter:off
        $sql = "ALTER TABLE `" . TB.PREFIX . "customers`
                ADD `parent_customer_id` INT( 11 ) NULL AFTER `custom_field4`;";
        // @formatter:on
        if ($dbh->exec($sql) === false) {
            error_log("sub_customer.php - addParentCustomerId(): Unable to perform request: sql[$sql]");
            return false;
        }
        return true;
    }

    public static function insertCustomer() {
        global $config;

        $enc = new encryption();
        $key = $config->encryption->default->key;
        $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
        try {
            pdoRequest('INSERT', 'customers', array('id'));
        } catch (Exception $e) {
            echo '<h1>Unable to add the new ' . TB_PREFIX . 'customer record.</h1>';
        }
    }

    public static function updateCustomer() {
        global $db;
        global $config;

        // $encrypted_credit_card_number = '';
        $exclude_fields = array("id");
        if ($is_new_cc_num = ($_POST['credit_card_number_new'] !='')) {
            $enc = new encryption();
            $key = $config->encryption->default->key;
            $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number_new']);
        } else {
            $exclude_fields = "credit_card_number";
        }
        try {
            $whereClause = new WhereClause();
            $whereClause->addItem(false, "id", "=", $GET['id'], false);
            pdoRequest('UPDATE', 'customers', $exclude_fields, $whereClause);
        } catch (Exception $e) {
            echo '<h1>Unable to update the ' . TB_PREFIX . 'customer record.</h1>';
        }
    }
}
