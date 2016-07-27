<?php
class SubCustomers {
    /**
     * Add extension database field if not present.
     * @return boolean true if no DB error; otherwise false.
     */
    public static function addParentCustomerId() {
        global $pdoDb;

        if (checkFieldExists(TB_PREFIX . "customers", "parent_customer_id")) return true;

        try {
            $sql = "ALTER TABLE `" . TB_PREFIX . "customers`
                    ADD `parent_customer_id` INT(11) NULL AFTER `custom_field4`;";
            $pdoDb->query($sql);
        } catch (Exception $e) {
            error_log("SubCustomers.php - addParentCustomerId(): " .
                      "Unable to perform request: sql[$sql]. " . print_r($e->getMessage(),true));
            return false;
        }
        return true;
    }

    /**
     * Add a new <b>si_customers</b> record.
     * @return boolean <b>true</b> if record successfully added; otherwise <b>false</b>.
     */
    public static function insertCustomer() {
        global $config, $pdoDb;

        $pdoDb->addSimpleWhere("name", $_POST['name'], "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $rows = $pdoDb->request("SELECT", "customers");
        if (!empty($rows)) {
            echo '<h1>The name you specified already exists.</h1>';
            return false; // Name already exists.
        }

        $saved = false;
        try {
            $excludeFields = array("id" => 1);
            if (empty($_POST['credit_card_number'])) {
                $excludeFields['credit_card_number'] = 1;
            } else {
                $key = $config->encryption->default->key;
                $enc = new Encryption();
                $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
            }

            $pdoDb->setExcludedFields($excludeFields);
            $pdoDb->request('INSERT', 'customers');
            $saved = true;
        } catch (Exception $e) {
            error_log("Unable to add the new " . TB_PREFIX . "customers record. Error reported: " . $e->getMessage());
            echo '<h1>Unable to add the new ' . TB_PREFIX . 'customer record.</h1>';
        }
        return $saved;
    }

    /**
     * Update an existing <b>si_customers</b> record.
     * @return boolean <b>true</b> if update is successful; otherwise <b>false</b>.
     */
    public static function updateCustomer() {
        global $config, $pdoDb;

        $saved = false;
        try {
            $excludedFields = array('id' => 1, 'domain_id' => 1);
            if (empty($_POST['credit_card_number'])) {
                $excludedFields['credit_card_number'] = 1;
            } else {
                $key = $config->encryption->default->key;
                $enc = new Encryption();
                $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
            }

            $pdoDb->setExcludedFields($excludedFields);
            $pdoDb->addSimpleWhere("id", $_GET['id']);
            $pdoDb->request('UPDATE', 'customers');
            $saved = true;
        } catch (Exception $e) {
            echo '<h1>Unable to update the ' . TB_PREFIX . 'customer record.</h1>';
            error_log("Unable to update the " . TB_PREFIX . "customers record. Error reported: " . $e->getMessage());
        }
        return $saved;
    }

    /**
     * Get a <b>sub-customer</b> records associated with a specific <b>parent_customer_id</b>.
     * @param number $parent_id ID of parent to which sub-customers are associated. 
     * @throws Exception If database access error occurs.
     * @return <b>si_customer</b> records retrieved.
     */
    public static function getSubCustomers($parent_id) {
        global $pdoDb;
        try {
            $pdoDb->addSimpleWhere("parent_customer_id", $parent_id, "AND");
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());
            $rows = $pdoDb->request("SELECT", "customers");
        } catch (PDOException $pde) {
            $str = "SubCustomers - getSubCustomers(): " . $pde->getMessage();
            error_log($str);
            throw new Exception($str);
        }
        return $rows;
    }
}
