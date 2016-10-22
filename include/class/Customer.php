<?php

class Customer {

    /**
     * Get a customer record.
     * @param string $id Unique ID record to retrieve.
     * @return array Row retrieved. Test for "=== false" to check for failure.
     */
    public static function get($id) {
        global $pdoDb;

        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("id", $id);
        $rows = $pdoDb->request("SELECT", "customers");
        return $rows[0];
    }

    /**
     * Retrieve all <b>customers</b> records per specified option.
     * @param boolean $enabled_only (Defaults to <b>false</b>) If set to <b>true</b> only Customers 
     *        that are <i>Enabled</i> will be selected. Otherwise all <b>customers</b> records are returned.
     * @return array Customers selected.
     */
    public static function get_all($enabled_only = false) {
        global $LANG, $pdoDb;

        if ($enabled_only) {
            $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
        }
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $pdoDb->setOrderBy("name");
        $rows = $pdoDb->request("SELECT", "customers");

        $customers = array();
        foreach($rows as $customer) {
            $customer['enabled'] = ($customer['enabled'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']);
            $customer['total']   = self::calc_customer_total($customer['id']);
            $customer['paid']    = Payment::calc_customer_paid($customer['id']);
            $customer['owing']   = $customer['total'] - $customer['paid'];
            $customers[]         = $customer;
        }

        return $customers;
    }
    public static function getCustomerInvoices($id) {
        global $pdoDb;
        $fn = new FunctionStmt("SUM", "COALESCE(ii.total,0)");
        $fr = new FromStmt("invoice_items", "ii");
        $wh = new WhereClause();
        $wh->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
        $wh->addSimpleItem("ii.domain_id", new DbField("iv.domain_id"));
        $se = new Select($fn, $fr, $wh, "invd");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("SUM", "COALESCE(ap.ac_amount, 0)");
        $fr = new FromStmt("payment", "ap");
        $wh = new WhereClause();
        $wh->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
        $wh->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
        $se = new Select($fn, $fr, $wh, "pmt");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("COALESCE", "invd, 0");
        $se = new Select($fn, null, null, "total");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("COALESCE", "pmt, 0");
        $se = new Select($fn, null, null, "paid");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("", "total - paid");
        $se = new Select($fn, null, null, "owing");
        $pdoDb->addToSelectStmts($se);

        $pdoDb->setSelectList(array("iv.id", "iv.index_id", "iv.date", "iv.type_id",
                                    "pr.status", "pr.pref_inv_wording"));

        $jn = new Join("LEFT", "preferences", "pr");
        $oc = new OnClause();
        $oc->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
        $oc->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
        $jn->setOnClause($oc);
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("iv.customer_id", $id, "AND");
        $pdoDb->addSimpleWhere("iv.domain_id", domain_id::get());

        $pdoDb->setOrderBy(array("iv.id", "D"));

        $rows = $pdoDb->request("SELECT", "invoices", "iv");

        $invoices = array();
        foreach ($rows as $row) {
            $row['calc_date'] = date('Y-m-d', strtotime($row['date']));
            $row['date'] = siLocal::date($row['date']);
            $invoices[] = $row;
        }

        return $invoices;
    }

    /**
     * Get a default customer name.
     * @param string $domain_id Domain user is logged into.
     * @return string Default customer name
     */
    public static function getDefaultCustomer($domain_id = '') {
        global $pdoDb;

        $pdoDb->addSimpleWhere("s.name", "customer", "AND");
        $pdoDb->addSimpleWhere("s.domain_id", domain_id::get());

        $jn = new Join("LEFT", "customers", "c");
        $jn->addSimpleItem("c.id", new DbField("s.value"), "AND");
        $jn->addSimpleItem("c.domain_id", new DbField("s.domain_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->setSelectList(array("c.name AS name", "s.value AS id"));
        $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        return $rows[0];
    }

    public static function calc_customer_total($customer_id, $isReal = false) {
        global $pdoDb;
        $pdoDb->addToFunctions(new FunctionStmt("COALESCE", "SUM(ii.total),0", "total"));

        $jn = new Join("INNER", "invoices", "iv");
        $jn->addSimpleItem("iv.id", new DbField("ii.invoice_id"), "AND");
        $jn->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
        $pdoDb->addToJoins($jn);

        if ($isReal) {
            $jn = new Join("LEFT", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
        }

        $pdoDb->addSimpleWhere("iv.customer_id", $customer_id, "AND");
        $pdoDb->addSimpleWhere("ii.domain_id", domain_id::get());

        $rows = $pdoDb->request("SELECT", "invoice_items", "ii");
        return $rows[0]['total'];
    }

}
