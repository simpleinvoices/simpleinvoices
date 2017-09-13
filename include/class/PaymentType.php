<?php

class PaymentType {
    /**
     * Get ID for payment type and set the type up if it doesn't exist.
     * @param string $description This is the payment type.
     * @return string ID of payment type record.
     */
    public static function select_or_insert_where($description) {
        global $pdoDb;

        $pdoDb->addSimpleWhere("pt_description", $description, "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $pdoDb->addToFunctions("COUNT(DISTINCT pt_id)", "count");

        $expr_list = "pt_id";
        $pdoDb->setSelectList($expr_list);
        $pdoDb->setGroupBy($expr_list);

        $rows =$pdoDb->request("SELECT", "payment_types");
        if (empty($rows)) {
            //add new payment type if no Paypal type
            self::insert($description, ENABLED);
            return self::select_or_insert_where($description);
        }

        return $rows[0]['pt_id'];
    }

    /**
     * Get a default payment type.
     * @param string $domain_id Domain user is logged into.
     * @return string Default payment type.
     */
    public static function getDefaultPaymentType($domain_id = '') {
        $domain_id = domain_id::get($domain_id);
        // @formatter:off
        $sql = "SELECT p.pt_description AS pt_description
            FROM " . TB_PREFIX . "payment_types p, " .
                TB_PREFIX . "system_defaults s
            WHERE ( s.name      = 'payment_type'
                AND p.pt_id     = s.value
                AND p.domain_id = s.domain_id
                AND s.domain_id = :domain_id)";
                // @formatter:on
                $sth = dbQuery($sql, ':domain_id', $domain_id);
                return $sth->fetch();
    }

    /**
     * Get a specific payment type record.
     * @param int $id Unique ID of record to retrieve.
     * @return array Row retrieved. Test for "=== false" to check for failure.
     *         Note that a field named, "enabled", was added to store the $LANG
     *         enable or disabled word depending on the "pref_enabled" setting
     *         of the record.
     */
    public static function select($id) {
        global $LANG, $pdoDb;

        $pdoDb->addSimpleWhere("pt_id", $id, "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $ca = new CaseStmt("pt_enabled", "enabled");
        $ca->addWhen( "=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setSelectAll(true);

        $rows = $pdoDb->request("SELECT", "payment_types");
        return (empty($rows) ? $rows : $rows[0]);
    }

    /**
     * Get payment type records.
     * @param boolean $active Set to <b>true</b> if you only want active payment types.
     *        Set to <b>false</b> or don't specify if you want all payment types.
     * @return array Rows retrieved. Note that an empty array is returned if no
     *         records are found.
     *         Note that a field named, "pt_enabled", was added to store the $LANG
     *         enable or disabled word depending on the "pref_enabled" setting
     *         of the record.
     */
    public static function select_all($active=false) {
        global $LANG, $pdoDb;

        if ($active) {
            $pdoDb->addSimpleWhere("pt_enabled", ENABLED, "AND");
        }
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $ca = new CaseStmt("pt_enabled", "pt_enabled");
        $ca->addWhen( "=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setOrderBy("pt_description");

        $pdoDb->setSelectAll(true);
        $rows = $pdoDb->request("SELECT", "payment_types");
        return $rows;
    }

    /**
     * Insert a new record using the values in the class properties.
     * @param string $pt_description Payment type description.
     * @param string $pt_enabled Set to constant, <b><i>ENABLED</i></b> or <b><i>DISABLED</i></b>.
     * @return Unique <b>pt_id</b> value assigned to the new record.
     */
    public static function insert($pt_description, $pt_enabled) {
        global $pdoDb;
        $pdoDb->setExcludedFields("pt_id");
        $pdoDb->setFauxPost(array("pt_description" => $pt_description,
                                  "pt_enabled"     => ($pt_enabled == ENABLED ? ENABLED : DISABLED),
                                  "domain_id"      => domain_id::get()));
        return $pdoDb->request("INSERT", "payment_types");
    }

    /**
     * Update an existing record using the values in the class properties.
     * @param string $pt_id Unique ID for this record.
     * @param string $pt_description Payment type description.
     * @param string $pt_enabled Set to constant, <b><i>ENABLED</i></b> or <b><i>DISABLED</i></b>.
     * @return boolean <b>true</b> if update was successful; otherwise <b>false</b>.
     */
    public static function update($pt_id, $pt_description, $pt_enabled) {
        global $pdoDb;
        $pdoDb->setExcludedFields(array("pt_id", "domain_id"));
        $pdoDb->setFauxPost(array("pt_description" => $pt_description,
                                  "pt_enabled"     => ($pt_enabled == ENABLED ? ENABLED : DISABLED)));

        // Note that we don't need to include the domain_id in the key since this is a unique key.
        $pdoDb->addSimpleWhere("pt_id", $pt_id);

        return $pdoDb->request("UPDATE", "payment_types");
    }
}
