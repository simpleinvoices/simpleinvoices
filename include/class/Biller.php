<?php
class Biller {
    /**
     * Get all biller records.
     * @param boolean $active_only Set to <b>true</b> to get active billers only.
     *        Set to <b>false</b> or don't specify anything if you want all billers.
     * @return array Biller records retrieved.
     */
    public static function get_all($active_only=false) {
        global $LANG, $pdoDb;

        if ($active_only) {
            $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
        }
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $ca =new CaseStmt("enabled", "wording_for_enabled");
        $ca->addWhen("=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setOrderBy("name");

        $pdoDb->setSelectAll(true);

        $billers = $pdoDb->request("SELECT", "biller");
        return $billers;
    }

    /**
     * Retrieve a specified biller record.
     * @param string $id ID of the biller to retrieve.
     * @return array Associative array for record retrieved.
     */
    public static function select($id) {
        global $LANG, $pdoDb;
        
        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("id", $id);

        $ca =new CaseStmt("enabled", "wording_for_enabled");
        $ca->addWhen("=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setSelectAll(true);

        $rows = $pdoDb->request("SELECT", "biller");
        return $rows[0];
    }

    /**
     * Get a default biller name.
     * @return string Default biller name
     */
    public static function getDefaultBiller() {
        $domain_id = domain_id::get();
        // @formatter:off
        $sql = "SELECT b.name AS name FROM " .
                TB_PREFIX . "biller b, " .
                TB_PREFIX . "system_defaults s
            WHERE ( s.name      = 'biller'
                AND b.id        = s.value
                AND b.domain_id = s.domain_id
                AND s.domain_id = :domain_id)";
                // @formatter:on
                $sth = dbQuery($sql, ':domain_id', $domain_id);
                return $sth->fetch();
    }

    /**
     * Insert a new biller record
     * @return integer|boolean ID if successful, test "=== false" if failed.
     */
    public static function insertBiller() {
        global $pdoDb;
        $_POST['domain_id'] = domain_id::get();
        if (empty($_POST['custom_field1'])) $_POST['custom_field1'] = "";
        if (empty($_POST['custom_field2'])) $_POST['custom_field2'] = "";
        if (empty($_POST['custom_field3'])) $_POST['custom_field3'] = "";
        if (empty($_POST['custom_field4'])) $_POST['custom_field4'] = "";

        $_POST['notes'] = (empty($_POST['note']) ? "" : trim($_POST['note']));
    
        $pdoDb->setExcludedFields("id");
        // @formatter:off
        /* All values in the $_POST array. No need for faux post file.
        $pdoDb->setFauxPost(array('street_address'        => $_POST['street_address'],
                                  'street_address2'       => $_POST['street_address2'],
                                  'city'                  => $_POST['city'],
                                  'state'                 => $_POST['state'],
                                  'zip_code'              => $_POST['zip_code'],
                                  'country'               => $_POST['country'],
                                  'phone'                 => $_POST['phone'],
                                  'mobile_phone'          => $_POST['mobile_phone'],
                                  'fax'                   => $_POST['fax'],
                                  'email'                 => $_POST['email'],
                                  'logo'                  => $_POST['logo'],
                                  'footer'                => $_POST['footer'],
                                  'paypal_business_name'  => $_POST['paypal_business_name'],
                                  'paypal_notify_url'     => $_POST['paypal_notify_url'],
                                  'paypal_return_url'     => $_POST['paypal_return_url'],
                                  'eway_customer_id'      => $_POST['eway_customer_id'],
                                  'paymentsgateway_api_id'=> $_POST['paymentsgateway_api_id'],
                                  'notes'                 => $_POST['notes'],
                                  'custom_field1'         => $_POST['custom_field1'],
                                  'custom_field2'         => $_POST['custom_field2'],
                                  'custom_field3'         => $_POST['custom_field3'],
                                  'custom_field4'         => $_POST['custom_field4'],
                                  'enabled'               => $_POST['enabled'],
                                  'domain_id'             => $_POST['domain_id']));
      */
      // @formatter:on
      $id = $pdoDb->request("INSERT", "biller");
      return !empty($id);
    }

    /**
     * Update <b>biller</b> table record.
     * @return boolean <b>true</b> if update successful
     */
    public static function updateBiller() {
        global $pdoDb;
        // The fields to be update must be in the $_POST array indexed by their
        // actual field name.
        $pdoDb->setExcludedFields(array("id", "domain_id"));
        $pdoDb->addSimpleWhere("id", $_GET['id']);
        return $pdoDb->request("UPDATE", "biller");
    }

}
